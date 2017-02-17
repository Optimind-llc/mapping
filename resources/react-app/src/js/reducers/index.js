import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';
// My reducers
import Application from './application';
import alert from './alert';

import PressReportData from '../modules/Press/report/ducks/report';

const rootReducer = combineReducers(Object.assign({
  Application,
  alert,
  PressReportData,
  routing: routerReducer
}));

export default rootReducer;
