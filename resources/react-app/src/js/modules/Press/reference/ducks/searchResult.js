import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../../middleware/fetchMiddleware';

export const SERCH_RESULT_DATA = 'SERCH_RESULT_DATA';
export const SERCH_RESULT_DATA_SUCCESS = 'SERCH_RESULT_DATA_SUCCESS';
export const SERCH_RESULT_DATA_FAIL = 'SERCH_RESULT_DATA_FAIL';
export const CLEAR_RESULT_DATA = 'CLEAR_RESULT_DATA';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case SERCH_RESULT_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case SERCH_RESULT_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case SERCH_RESULT_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case CLEAR_RESULT_DATA:
      return Object.assign({}, state, {
        data: null,
        isFetching: false,
        didInvalidate: false
      });

    default:
      return state;
  }
}

export function searchResult(l, v, p, judge, failures, s, e, chokus) {
  return {
    [CALL_API]: {
      types: [
        SERCH_RESULT_DATA,
        SERCH_RESULT_DATA_SUCCESS,
        SERCH_RESULT_DATA_FAIL
      ],
      endpoint: '/press/manager/reference/search/inspection',
      method: 'POST',
      body: { l, v, p, judge, failures, s, e, chokus }
    }
  };
}

export function clearResultData() {
  return {
    type: CLEAR_RESULT_DATA
  }
}

export const searchResultActions = {
  searchResult,
  clearResultData
};
