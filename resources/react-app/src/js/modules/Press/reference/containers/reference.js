import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import { vehicles, parts, processes, inspections, inspectionGroups } from '../../../../utils/Processes';
// Actions
import { push } from 'react-router-redux';
import { failureTypeActions } from '../ducks/failureType';
import { searchResultActions } from '../ducks/searchResult';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Styles
import './reference.scss';
// Components
import SearchButton from '../../../../components/buttons/search/searchButton';
import CustomCalendar from '../components/calendar/calendar';
import CustomTable from '../components/customTable/customTable';

class Reference extends Component {
  constructor(props, context) {
    super(props, context);

    props.actions.getFailureTypes();

    this.state = {
      line: null,
      vehicle: null,
      part: null,
      choku: {label: '白直', value: ['W']},
      startDate: moment(),
      endDate: moment(),
      judgement: {label: '両方', value: [0, 1]},
      hasFailures: []
    };
  }

  getInspectionGroup() {
    const { vehicle, partTId, processId, inspectionId } = this.state;

    const filteredInspectionGroup = inspectionGroups.filter(ig =>
      ig.vehicle == vehicle.value &&
      (partTId ? (ig.part == partTId.value) : false) &&
      (processId ? (ig.p == processId.value) : false) &&
      (inspectionId ? (ig.i == inspectionId.value) : false) &&
      !ig.disabled
    );

    let inspectionGroupId = 0;
    if (filteredInspectionGroup.length > 0) {
      inspectionGroupId = filteredInspectionGroup[0].iG;
    }

    return inspectionGroupId;
  }

  search() {
    const { searchResult } = this.props.actions;
    const { line, vehicle, part, choku, startDate, endDate, judgement, hasFailures } = this.state;
    const format = 'YYYY-MM-DD';

    searchResult(
      line === null ? line : line.value,
      vehicle === null ? line : vehicle.value,
      part.value,
      judgement.value,
      hasFailures.map(f => f.value),
      startDate.format(format),
      endDate.format(format),
      choku.value
    );
  }

  render() {
    const { InitialData, FailureTypeData, SearcheResult, actions } = this.props;
    const { line, vehicle, part, choku, startDate, endDate, judgement, hasFailures } = this.state;

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
      <div id="press-referance-wrap">
        <div className="bg-white referance-header">
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
          </div>
          <div className="row">
            <p>判定：</p>
            <Select
              name="判定"
              className="judgement"
              placeholder="判定を選択"
              clearable={false}
              Searchable={true}
              value={judgement}
              options={[
                {label: '○', value: [1]},
                {label: '×', value: [0]},
                {label: '両方', value: [0, 1]}
              ]}
              onChange={value => this.setState({judgement: value})}
            />
            <p>不良：</p>
            <Select
              name="不良"
              className="failure-types"
              placeholder={judgement.label === '×' ? '含める不良を選択' : '判定×を選択時有効'}
              clearable={false}
              Searchable={false}
              disabled={judgement.label !== '×'}
              multi={true}
              value={ hasFailures }
              options={FailureTypeData.data ? FailureTypeData.data.map(f => {return {label: f.name, value: f.id};}) : []}
              onChange={value => this.setState({hasFailures: value})}
            />
            <div
              className="row"
              onClick={() => this.setState({narrowedBy: 'date'})}
            >
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
          <div className="row">
            <SearchButton
              active={part !== null && startDate !== null && endDate !== null}
              searching={false}
              onClick={() => this.search()}
            />
          </div>
        </div>
        <div className="result-wrap bg-white">
          {
            !SearcheResult &&
            <p>検索中...</p>
          }{
            SearcheResult.data != null &&
            <CustomTable
              count={SearcheResult.data.result_count}
              failureTypes={SearcheResult.data.failureTypes}
              result={SearcheResult.data.result}
              download={() => handleDownload(table)}
            />
          }
        </div>
      </div>
    );
  }
}

Reference.propTypes = {
  InitialData: PropTypes.object.isRequired,
  FailureTypeData: PropTypes.object.isRequired,
  SearcheResult: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    InitialData: state.Application.press,
    FailureTypeData: state.PressFailureTypeData,
    SearcheResult: state.PressSearchInspectionResult
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push},
    failureTypeActions,
    searchResultActions
  );

  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Reference);
