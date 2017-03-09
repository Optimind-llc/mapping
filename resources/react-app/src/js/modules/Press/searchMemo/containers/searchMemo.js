import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import Select from 'react-select';
import { vehicles, parts, processes, inspections, inspectionGroups } from '../../../../utils/Processes';
// Actions
import { push } from 'react-router-redux';
import { searchMemoActions } from '../ducks/searchMemo';
// Material-ui Components
import { Paper, Dialog, RaisedButton, FlatButton } from 'material-ui';
import { grey50, indigo500 } from 'material-ui/styles/colors';
// Styles
import './searchMemo.scss';
// Components
import SearchButton from '../../../../components/buttons/search/searchButton';
import CustomCalendar from '../components/calendar/calendar';
import CustomTable from '../components/customTable/customTable';

class SearchMemo extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      line: null,
      vehicle: null,
      part: null,
      choku: {label: '白直', value: ['W']},
      startDate: moment(),
      endDate: moment()
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
    const { searchMemo } = this.props.actions;
    const { line, vehicle, part, startDate, endDate } = this.state;
    const format = 'YYYY-MM-DD';

    searchMemo(
      line === null ? line : line.value,
      vehicle === null ? vehicle : vehicle.value,
      part.value,
      startDate.format(format),
      endDate.format(format)
    );
  }

  render() {
    const { InitialData, FailureTypeData, SearchMemo, actions } = this.props;
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
      <div id="press-search-memo-wrap">
        <div className="bg-white referance-header">
          <div className="row">
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
            <SearchButton
              active={part !== null && startDate !== null && endDate !== null}
              searching={false}
              onClick={() => this.search()}
            />
          </div>
        </div>
        <div className="result-wrap bg-white">
          {
            SearchMemo.isFetching &&
            <p className="center-message">検索中...</p>
          }{
            SearchMemo.data != null &&SearchMemo.data.result_count > 0 &&
            <CustomTable
              count={SearchMemo.data.result_count}
              failureTypes={SearchMemo.data.failureTypes}
              result={SearchMemo.data.result}
              download={() => handleDownload(table)}
            />
          }{
            !SearchMemo.isFetching && SearchMemo.data != null &&SearchMemo.data.result_count === 0 &&
            <p className="center-message">検索結果なし</p>
          }
        </div>
      </div>
    );
  }
}

SearchMemo.propTypes = {
  InitialData: PropTypes.object.isRequired,
  FailureTypeData: PropTypes.object.isRequired,
  SearchMemo: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    InitialData: state.Application.press,
    SearchMemo: state.PressSearchMemoResult
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push},
    searchMemoActions
  );

  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(SearchMemo);
