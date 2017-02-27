import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';
// My reducers
import Application from './application';
import alert from './alert';

import PressReportData from '../modules/Press/report/ducks/report';
import PressMappingData from '../modules/Press/mapping/ducks/mapping';
import PressFailureTypeData from '../modules/Press/reference/ducks/failureType';
import PressSearchInspectionResult from '../modules/Press/reference/ducks/searchResult';
import PressSearchMemoResult from '../modules/Press/searchMemo/ducks/searchMemo';

const rootReducer = combineReducers(Object.assign({
  Application,
  alert,
  PressReportData,
  PressMappingData,
  PressFailureTypeData,
  PressSearchInspectionResult,
  PressSearchMemoResult,
  routing: routerReducer
}));

export default rootReducer;
