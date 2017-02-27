import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import { vehicles, processes, inspections, inspectionGroups } from '../../../../utils/Processes';
// Actions
import { push } from 'react-router-redux';
import { pageActions } from '../ducks/mapping';
// Styles
import './mapping.scss';
// Components
import CustomCalendar from '../components/calendar/calendar';
import MappingBody from '../components/body/mappingBody';

import SearchButton from '../../../../components/buttons/search/searchButton';
import SaveButton from '../../../../components/buttons/save/saveButton';
// import Loading from '../../../components/loading/loading';
// import RangeCalendar from '../components/rangeCalendar/rangeCalendar';

class Mapping extends Component {
  constructor(props, context) {
    super(props, context);
    const { InitialData, MappingData, actions } = props;

    this.state = {
      line: null,
      vehicle: null,
      part: null,
      narrowedBy: 'realtime',
      choku: {label: '白直', value: ['W']},
      startDate: moment(),
      endDate: moment(),
      intervalId: null,
      interval: 10000,
    };
  }

  componentWillUnmount() {
    clearInterval(this.state.intervalId);
    this.props.actions.clearMappingData();
  }

  startInterval() {    
    const intervalId = setInterval(
      () => this.requestMappingData(),
      this.state.interval
    );

    this.setState({intervalId});
  }

  endInterval() {
    clearInterval(this.state.intervalId);
  }

  serchItorG() {
    const { getItorGData } = this.props.actions;
    getItorGData();
  }

  requestMappingData() {
    const { line, vehicle, part, narrowedBy, choku, startDate, endDate } = this.state;
    const { actions } = this.props;

    const format = 'YYYY-MM-DD';

    const l = line === null ? null : line.value;
    const v = vehicle === null ? null : vehicle.value;
    const p = part.value;
    const c = choku.value;

    actions.getMappingData(l, v, p, narrowedBy, c, startDate.format(format), endDate.format(format));
  }

  render() {
    const { line, vehicle, part, narrowedBy, choku, startDate, endDate, intervalId } = this.state;
    const { InitialData, MappingData, actions } = this.props;

    const lines = InitialData.lines.map(l => { return {label: l, value: l} });
    const vehicles = InitialData.vehicles.map(v => { return {label: v, value: v} });
    const parts = InitialData.combinations.filter(c =>
      (line === null || (line !== null && line.value === c.l)) &&
      (vehicle === null || (vehicle !== null && vehicle.value === c.v))
    ).map(c => {
      return {
        label: c.p, value: c.p
      }
    }).sort((a,b) => {
      if(a.value < b.value) return -1;
      if(a.value > b.value) return 1;
      return 0;
    });

    return (
      <div id="press-mapping-wrap">
        <div className="bg-white mapping-header">
          <div className="select-wrap">
            <div className="row">
              <p>ライン：</p>
              <Select
                name="ライン"
                placeholder="全てのライン"
                clearable={true}
                Searchable={true}
                value={line}
                options={lines}
                onChange={value => this.setState({line: value})}
              />
              <p>車種：</p>
              <Select
                name="車種"
                placeholder="全ての車種"
                clearable={true}
                Searchable={true}
                value={vehicle}
                options={vehicles}
                onChange={value => this.setState({vehicle: value})}
              />
              <p>品番*：</p>
              <Select
                name="品番"
                placeholder="品番を選択"
                clearable={false}
                Searchable={true}
                value={part}
                options={parts}
                onChange={value => this.setState({part: value})}
              />
            </div>
            <div className="row">
              <div
                className={`row selectable ${narrowedBy === 'realtime' ? 'selected' : ''}`}
                onClick={() => this.setState({narrowedBy: 'realtime'})}
              >
                <p className="realtime">リアルタイム</p>
              </div>
              <div
                className={`row selectable ${narrowedBy === 'date' ? 'selected' : ''}`}
                onClick={() => this.setState({narrowedBy: 'date'})}
              >
                <p>直：</p>
                <Select
                  name="直"
                  placeholder="直を選択"
                  clearable={false}
                  Searchable={true}
                  value={choku}
                  options={[
                    {label: '白直', value: ['W']},
                    {label: '黄直', value: ['Y']},
                    {label: '両直', value: ['W', 'Y']}
                  ]}
                  onChange={value => this.setState({choku: value})}
                />
                <p>期間：</p>
                <CustomCalendar
                  defaultDate={startDate}
                  changeDate={d => this.setState({startDate: d})}
                  disabled={false}
                />
                <p>〜</p>
                <CustomCalendar
                  defaultDate={endDate}
                  changeDate={d => this.setState({endDate: d})}
                  disabled={false}
                />
              </div>
            </div>
          </div>
          <button
            className={`show ${part === null ? 'disabled' : ''}`}
            onClick={() => {
              this.requestMappingData();
              clearInterval(intervalId);

              if (narrowedBy === 'realtime') {
                this.startInterval();
              }
            }}
          >
            表示
          </button>
        </div>
        {
          MappingData.data !== null &&
          <MappingBody
            data={MappingData.data}
            isFetching={MappingData.isFetching}
            didInvalidate={MappingData.didInvalidate}
            narrowedBy={narrowedBy}
          />
        }
      </div>
    );
  }
}

Mapping.propTypes = {
  InitialData: PropTypes.object.isRequired,
  MappingData: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    InitialData: state.Application.press,
    MappingData: state.PressMappingData
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push}, pageActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Mapping);
