import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../../middleware/fetchMiddleware';

export const SERCH_MEMO_DATA = 'SERCH_MEMO_DATA';
export const SERCH_MEMO_DATA_SUCCESS = 'SERCH_MEMO_DATA_SUCCESS';
export const SERCH_MEMO_DATA_FAIL = 'SERCH_MEMO_DATA_FAIL';
export const CLEAR_MEMO_DATA = 'CLEAR_MEMO_DATA';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case SERCH_MEMO_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case SERCH_MEMO_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case SERCH_MEMO_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    case CLEAR_MEMO_DATA:
      return Object.assign({}, state, {
        data: null,
        isFetching: false,
        didInvalidate: false
      });

    default:
      return state;
  }
}

export function searchMemo(l, v, p, s, e) {
  return {
    [CALL_API]: {
      types: [
        SERCH_MEMO_DATA,
        SERCH_MEMO_DATA_SUCCESS,
        SERCH_MEMO_DATA_FAIL
      ],
      endpoint: '/press/manager/reference/search/memo',
      method: 'POST',
      body: { l, v, p, s, e }
    }
  };
}

export function clearMemoData() {
  return {
    type: CLEAR_MEMO_DATA
  }
}

export const searchMemoActions = {
  searchMemo,
  clearMemoData
};
