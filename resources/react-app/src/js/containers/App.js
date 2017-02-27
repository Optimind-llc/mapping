import React, { Component, PropTypes } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
// Config
import { PASS_WORD } from '../../config/env';
// Actions
import { applicationActions } from '../reducers/application';
import { push } from 'react-router-redux';
// Components
import Alert from '../components/alert/alert';
import Navigation from '../components/navigation/navigation';

class App extends Component {
  constructor(props, context) {
    super(props, context);
    const { changeProcess, getPressInitial } = this.props.actions;

    changeProcess('press');
    getPressInitial();
  }

  render() {
    const { children, routes, Application, alerts, actions } = this.props;

    const links = {
      'press': [
        // { path: '/press/manager/dashboard', name: 'ダッシュボード', disable: false},
        { path: '/press/manager/mapping', name: 'マッピング', disable: false },
        { path: '/press/manager/reference', name: '検査結果検索', disable: false },
        { path: '/press/manager/report', name: '直レポート印刷', disable: false },
        { path: '/press/manager/contact', name: '手直連絡票検索', disable: false }
      ],
      'body': [
        // { path: '/body/manager/dashboard', name: 'ダッシュボード', disable: true},
        { path: '/body/manager/mapping', name: 'マッピング', disable: true},
        { path: '/body/manager/reference', name: '検査結果検索', disable: true },
        { path: '/body/manager/report', name: '直レポート印刷', disable: true },
        { path: '/body/manager/contact', name: '手直連絡票検索', disable: true }
      ]
    };

    const masterlinks = {
      'press': [
        { path: '/press/maintenance/worker', name: '担当者マスタ', disable: true },
        { path: '/press/maintenance/failure', name: '不良区分マスタ', disable: true },
        { path: '/press/maintenance/part', name: '品番マスタ', disable: true },
        { path: '/press/maintenance/part', name: '部品ペアマスタ', disable: true }
      ],
      'body': [
        { path: '/body/maintenance/worker', name: '担当者マスタ', disable: true },
        { path: '/body/maintenance/failure', name: '不良区分マスタ', disable: true },
        { path: '/body/maintenance/part', name: '品番マスタ', disable: true },
        { path: '/press/maintenance/part', name: '部品ペアマスタ', disable: true }
      ]
    };

    const styles = {
      container: {
        minWidth: 1349,
        backgroundColor: 'rgba(231,236,245,1)',
        height: '100%',
        minHeight: 600
      },
      content: {
        paddingLeft: 160,
      }
    };

    return (
      <div style={styles.container}>
        <Alert alerts={alerts} deleteSideAlerts={actions.deleteSideAlerts} />
        <Navigation
          processEn={Application.process}
          links={links[Application.process]}
          masterlinks={masterlinks[Application.process]}
          logedin={Application.master}
          pw={PASS_WORD}
          login={() => actions.login()}
          logout={() => actions.logout()}
          changeProcess={p => this.props.actions.changeProcess(p)}
          push={actions.push}
        />
        <div style={styles.content}>
          {children}
        </div>
      </div>
    );
  }
}

App.propTypes = {
  children: PropTypes.element.isRequired,
  routes: PropTypes.array.isRequired,
  alerts: PropTypes.array,
  actions: PropTypes.object.isRequired
};

function mapStateToProps(state, ownProps) {
  return {
    Application: state.Application,
    alerts: state.alert,
    routes: ownProps.routes
  };
}

function mapDispatchToProps(dispatch) {
  const actions = Object.assign(
    applicationActions,
    { push: push }
  );

  return {
    actions: bindActionCreators(actions, dispatch)
  };
}

export default connect(mapStateToProps, mapDispatchToProps)(App);
