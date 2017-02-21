import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../middleware/fetchMiddleware';

export const LOG_IN = 'LOG_IN';
export const LOG_OUT = 'LOG_OUT';
export const CHANGE_PROCESS = 'CHANGE_PROCESS';

export const REDUEST_PRESS_INITIAL_DATA = 'REDUEST_PRESS_INITIAL_DATA';
export const REDUEST_PRESS_INITIAL_DATA_SUCCESS = 'REDUEST_PRESS_INITIAL_DATA_SUCCESS';
export const REDUEST_PRESS_INITIAL_DATA_FAIL = 'REDUEST_PRESS_INITIAL_DATA_FAIL';

const initialState = {
  master: false,
  process: 'press',
  press: {
    chokus: [],
    vehicles: [],
    lines: [],
    parts: [],
    combinations: []
  },
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case LOG_IN:
      return Object.assign({}, state, {
        master: true
      });

    case LOG_OUT:
      return Object.assign({}, state, {
        master: false
      });

    case CHANGE_PROCESS:
      return Object.assign({}, state, {
        process: action.payload.process
      });

    case REDUEST_PRESS_INITIAL_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_PRESS_INITIAL_DATA_SUCCESS:
      return Object.assign({}, state, {
        press: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_PRESS_INITIAL_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

export function login() {
  return {
    type: LOG_IN
  };
}

export function logout() {
  return {
    type: LOG_OUT
  };
}

export function changeProcess(p) {
  return {
    type: CHANGE_PROCESS,
    payload: { process: p }
  };
}

export function getPressInitial() {
  return {
    [CALL_API]: {
      types: [
        REDUEST_PRESS_INITIAL_DATA,
        REDUEST_PRESS_INITIAL_DATA_SUCCESS,
        REDUEST_PRESS_INITIAL_DATA_FAIL
      ],
      endpoint: 'press/manager/initial',
      method: 'GET',
      body: null
    }
  };
}



export const applicationActions = {
  login,
  logout,
  changeProcess,
  getPressInitial
};
