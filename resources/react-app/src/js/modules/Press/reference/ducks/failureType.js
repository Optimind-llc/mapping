import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../../middleware/fetchMiddleware';

export const REQUEST_FAILURE_TYPE_DATA = 'REQUEST_FAILURE_TYPE_DATA';
export const REQUEST_FAILURE_TYPE_DATA_SUCCESS = 'REQUEST_FAILURE_TYPE_DATA_SUCCESS';
export const REQUEST_FAILURE_TYPE_DATA_FAIL = 'REQUEST_FAILURE_TYPE_DATA_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REQUEST_FAILURE_TYPE_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REQUEST_FAILURE_TYPE_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REQUEST_FAILURE_TYPE_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

export function getFailureTypes(itionGId) {
  return {
    [CALL_API]: {
      types: [
        REQUEST_FAILURE_TYPE_DATA,
        REQUEST_FAILURE_TYPE_DATA_SUCCESS,
        REQUEST_FAILURE_TYPE_DATA_FAIL
      ],
      endpoint: '/press/manager/reference/failureTypes',
      method: 'GET',
      body: null
    }
  };
}

export const failureTypeActions = {
  getFailureTypes
};
