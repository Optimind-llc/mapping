import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import moment from 'moment';
import { vehicles, processes, inspections, inspectionGroups } from '../../../../utils/Processes';
// Actions
import { push } from 'react-router-redux';
import { reportActions } from '../ducks/report';
// Styles
import './report.scss';
// Components
// import Loading from '../../../components/loading/loading';
// import RangeCalendar from '../components/rangeCalendar/rangeCalendar';
import ReportHead from '../components/head/reportHead';
import ReportBody from '../components/body/reportBody';

class Report extends Component {
  constructor(props, context) {
    super(props, context);

    const { getReportData } = props.actions;
    const date = moment();
    const choku = {label: '白直', value: ['W']};

    getReportData(date.format('YYYY-MM-DD'), choku.value);

    this.state = ({
      date,
      choku,
      modal: false,
      path: '',
    });
  }

  componentWillUnmount() {
    this.props.actions.clearReportData();
  }

  serchReport(date, choku) {
    const { getReportData } = this.props.actions;

    getReportData(date.format('YYYY-MM-DD'), choku.value);
  }

  render() {
    const { date, choku } = this.state;
    const { InitialData, ReportData, actions } = this.props;

    return (
      <div id="press-report">
        <ReportHead
          defaultDate={date}
          changeDate={d => {
            this.setState({date: d});
            this.serchReport(d, choku);
          }}
          defaultChoku={choku}
          changeChoku={c => {
            this.setState({choku: c});
            this.serchReport(date, c);
          }}
        />
        {
          !InitialData.idFetching && !ReportData.idFetching && ReportData.data !== null &&
          <ReportBody
            lines={InitialData.lines}
            vehicles={InitialData.vehicles}
            combinations={InitialData.combinations}
            count={ReportData.data}
          />
        }
      </div>
    );
  }
}

Report.propTypes = {
  InitialData: PropTypes.object.isRequired,
  ReportData: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    InitialData: state.Application.press,
    ReportData: state.PressReportData
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign({push}, reportActions);
  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(Report);
