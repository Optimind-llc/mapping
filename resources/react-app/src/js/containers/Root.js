import React, { Component, PropTypes } from 'react';
import { Router, Route, IndexRoute, Redirect } from 'react-router';
import { Provider, connect } from 'react-redux';
//import DevTools from './DevTools';
// import injectTapEventPlugin from 'react-tap-event-plugin';
// injectTapEventPlugin();
//Components
import App from './App';
import PressDashboard from '../modules/Press/dashboard/containers/dashboard';
import PressMapping from '../modules/Press/mapping/containers/mapping';
import PressReport from '../modules/Press/report/containers/report';
import PressReference from '../modules/Press/reference/containers/reference';
import PressSearchMemo from '../modules/Press/searchMemo/containers/searchMemo';

class Root extends Component {
  render() {
    const { history, store } = this.props;
    return (
      <Provider store={store}>
        <Router history={history}>
          <Route name="press" path="press" component={App}>
            <Route path="manager">
              <Route name="ダッシュボード" path="dashboard-test" component={PressDashboard}/>
              <Route name="ダッシュボード" path="dashboard" component={PressMapping}/>
              <Route name="マッピング" path="mapping" component={PressMapping}/>
              <Route name="検査結果検索" path="reference" component={PressReference}/>
              <Route name="直レポート" path="report" component={PressReport}/>
              <Route name="手直連絡票検索" path="contact" component={PressSearchMemo}/>
            </Route>
            <Route path="maintenance">
              <Route name="担当者マスタ" path="worker" component={PressDashboard}/>
              <Route name="不良区分マスタ" path="failure" component={PressDashboard}/>
              <Route name="品番マスタ" path="modification" component={PressDashboard}/>
            </Route>
          </Route>
        </Router>
        {/*<DevTools/>*/}
      </Provider>
    );
  }
}

Root.propTypes = {};

function mapStateToProps(state) {
  return {};
}

export default connect(mapStateToProps)(Root);
