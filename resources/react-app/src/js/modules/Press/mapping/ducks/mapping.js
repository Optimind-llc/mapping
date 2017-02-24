import {fromJS, Map as iMap, List as iList} from 'immutable';
import { CALL_API } from '../../../../middleware/fetchMiddleware';

export const REDUEST_MAPPING_DATA = 'REDUEST_MAPPING_DATA';
export const REDUEST_MAPPING_DATA_SUCCESS = 'REDUEST_MAPPING_DATA_SUCCESS';
export const REDUEST_MAPPING_DATA_FAIL = 'REDUEST_MAPPING_DATA_FAIL';

const initialState = {
  data: null,
  isFetching: false,
  didInvalidate: false
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case REDUEST_MAPPING_DATA:
      return Object.assign({}, state, {
        isFetching: true,
        didInvalidate: false
      });

    case REDUEST_MAPPING_DATA_SUCCESS:
      return Object.assign({}, state, {
        data: action.payload.data,
        isFetching: false,
        didInvalidate: false
      });

    case REDUEST_MAPPING_DATA_FAIL:
      return Object.assign({}, state, {
        isFetching: false,
        didInvalidate: true
      });

    default:
      return state;
  }
}

// export function panelIdMapping(partT, itionG, itorG, panel) {
//   return {
//     [CALL_API]: {
//       types: [
//         REDUEST_MAPPING_DATA,
//         REDUEST_MAPPING_DATA_SUCCESS,
//         REDUEST_MAPPING_DATA_FAIL
//       ],
//       endpoint: `/show/mapping/panelId/${partT}/${itionG}/${itorG}/${panel}`,
//       method: 'GET',
//       body: null
//     }
//   };
// }

export function getMappingData(line, vehicle, part, narrowedBy, chokus = [], s = null, e = null) {
  return {
    [CALL_API]: {
      types: [
        REDUEST_MAPPING_DATA,
        REDUEST_MAPPING_DATA_SUCCESS,
        REDUEST_MAPPING_DATA_FAIL
      ],
      endpoint: '/press/manager/mapping',
      method: 'POST',
      body: { line, vehicle, part, narrowedBy, chokus, s, e }
    }
  };
}

export const pageActions = {
  // panelIdMapping,
  getMappingData
};
