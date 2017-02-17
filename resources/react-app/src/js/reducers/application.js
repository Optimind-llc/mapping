import {fromJS, Map as iMap, List as iList} from 'immutable';

export const LOG_IN = 'LOG_IN';
export const LOG_OUT = 'LOG_OUT';
export const CHANGE_PROCESS = 'CHANGE_PROCESS';

const initialState = {
  master: false,
  process: 'press'
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

export const applicationActions = {
  login,
  logout,
  changeProcess
};
