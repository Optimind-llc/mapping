import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';
// My reducers
import Application from './application';
import alert from './alert';

import PressReportData from '../modules/Press/report/ducks/report';
import PressMappingData from '../modules/Press/mapping/ducks/mapping';

const rootReducer = combineReducers(Object.assign({
  Application,
  alert,
  PressReportData,
  PressMappingData,
  routing: routerReducer
}));

export default rootReducer;
