import React, { Component, PropTypes } from 'react';
import { Table } from 'reactable';
import moment from 'moment';
import './customTable.scss';

class CustomTable extends Component {
  constructor(props, context) {
    super(props, context);
    this.state = {
      sort: {
        key: 'inspectedAt',
        asc: false,
        id: 0
      }
    };
  }

  sortData(data) {
    const { sort } = this.state;

    data.sort((a,b) => {
      let aaa = 0;
      let bbb = 0;

      if (sort.key == 'panelId') {
        aaa = a[sort.key].toLowerCase();
        bbb = b[sort.key].toLowerCase();
      }
      else if (sort.key == 'status') {
        aaa = a[sort.key];
        bbb = b[sort.key];
      }
      else if (sort.key == 'choku' || sort.key == 'inspectedBy' || sort.key == 'updatedBy') {
        aaa = a[sort.key];
        bbb = b[sort.key];
      }
      else if (sort.key == 'failures' || sort.key == 'modifications' || sort.key == 'hModifications') {
        if (a[sort.key][sort.id]) {
          aaa = a[sort.key][sort.id];
        }
        if (b[sort.key][sort.id]) {
          bbb = b[sort.key][sort.id];
        }
      }
      else if (sort.key == 'holes') {
        aaa = a[sort.key].find(h => h.id == [sort.id]).status;
        bbb = b[sort.key].find(h => h.id == [sort.id]).status;
      }
      else if (sort.key == 'inspectedAt' || sort.key == 'updatedAt') {
        aaa = moment(a[sort.key], 'YYYY-MM-DD HH:mm:ss 11:08:40').unix();
        bbb = moment(b[sort.key], 'YYYY-MM-DD HH:mm:ss 11:08:40').unix();
      }

      if (sort.asc) {
        if(aaa < bbb) return 1;
        else if(aaa > bbb) return -1;
      } else {
        if(aaa < bbb) return -1;
        else if(aaa > bbb) return 1;
      }
      return 0;
    });

    return data;
  }

  render() {
    const { count, result, failureTypes, download } = this.props;
    const { sort } = this.state;
    const colWidth = {
      number: 36,
      line: 50,
      vehicle: 44,
      pn: 100,
      choku: 38,
      At: 127,
      By: 80,
      paletNum: 80,
      status: 60,
      failure: 88,
      comment: 77
    };

    const failureTypes2 = failureTypes.reduce((pre, cur) => {
      return [...pre, cur, cur];
    }, []);

    let tableWidth = colWidth.number + colWidth.line + colWidth.vehicle + colWidth.pn + colWidth.paletNum + colWidth.choku + colWidth.By + colWidth.By + colWidth.status + colWidth.comment + colWidth.comment + colWidth.At + colWidth.At + colWidth.By + colWidth.At + colWidth.By + 18;
    if (failureTypes.length > 0) {
      tableWidth = tableWidth + colWidth.failure*failureTypes.length;
    }

    return (
      <div className="table-wrap">
        {
          count < 1000 &&
          <p className="result-count">{`${count}件中 ${count}件表示`}</p>
        }{
          count >= 1000 &&
          <p className="result-count">{`${count}件中 1000件表示`}</p>
        }
        {/*
        <button className="download dark" onClick={() => download()}>
          <p>CSVをダウンロード</p>
        </button>
        */}

        <table className="reference-result" style={{width: tableWidth}}>
          <thead>
            <tr>
              <th rowSpan="3" style={{width: colWidth.number}}>No.</th>
              <th rowSpan="3" style={{width: colWidth.line}}>ライン</th>
              <th rowSpan="3" style={{width: colWidth.vehicle}}>車種</th>
              <th rowSpan="3" style={{width: colWidth.pn}}>品番</th>
              <th
                rowSpan="3"
                style={{width: colWidth.At}}
                className={`clickable ${sort.key == 'inspectedAt' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'inspectedAt') this.setState({sort: { key: 'inspectedAt', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'inspectedAt', asc: true, id: 0 }});
                }}
              >
                加工日時
              </th>
              <th
                rowSpan="3"
                style={{width: colWidth.choku}}
                className={`clickable ${sort.key == 'choku' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'choku') this.setState({sort: { key: 'choku', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'choku', asc: true, id: 0 }});
                }}
              >
                直
              </th>
              <th
                rowSpan="3"
                style={{width: colWidth.By}}
                className={`clickable ${sort.key == 'inspectedBy' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'inspectedBy') this.setState({sort: { key: 'inspectedBy', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'inspectedBy', asc: true, id: 0 }});
                }}
              >
                検査者
              </th>
              <th
                rowSpan="3"
                style={{width: colWidth.paletNum}}
                className={`clickable ${sort.key == 'paletNum' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'paletNum') this.setState({sort: { key: 'paletNum', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'paletNum', asc: true, id: 0 }});
                }}
              >
                パレット連番
              </th>
              <th
                rowSpan="3"
                style={{width: colWidth.status}}
                className={`clickable ${sort.key == 'status' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'status') this.setState({sort: { key: 'status', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'status', asc: true, id: 0 }});
                }}
              >
                検査結果
              </th>
              {
                failureTypes.length > 0 &&
                <th colSpan={failureTypes.length*2}>不良区分</th>
              }
              <th rowSpan="3" style={{width: colWidth.comment}}>コメント</th>
              <th rowSpan="3" style={{width: colWidth.By}}>手直者</th>
              <th rowSpan="3" style={{width: colWidth.comment}}>手直者コメント</th>
              <th rowSpan="3" style={{width: colWidth.At}}>手直日時</th>
              <th rowSpan="3" style={{width: colWidth.By}}>再発行連番</th>
              <th rowSpan="3" style={{width: colWidth.At}}>納入先引取時間</th>
              <th rowSpan="3" style={{width: colWidth.By}}>納入先名称</th>
            </tr>
            <tr>
            {
              failureTypes.map(f =>
                <th
                  style={{width: colWidth.failure}}
                  colSpan={2}
                  className={`clickable ${(sort.key == 'failures' && sort.id == f.id) ? sort.asc ? 'asc' : 'desc' : ''}`}
                  onClick={() => {
                    if(sort.key == 'failures') this.setState({sort: { key: 'failures', asc: !sort.asc, id: f.id }});
                    else this.setState({sort: { key: 'failures', asc: true, id: f.id }});
                  }}
                >
                  {`${f.name}`}
                </th>
              )
            }
            </tr>
            <tr>
              {
                failureTypes2.map((f, i) =>
                  i % 2 === 0 ?
                  <th style={{width: colWidth.failure/2}}>不良</th> :
                  <th style={{width: colWidth.failure/2}}>手直</th>
                )
              }
            </tr>
          </thead>
          <tbody>
          {
            result.map((r,i) =>
              <tr>
                <td style={{width: colWidth.number}}>{i+1}</td>
                <td style={{width: colWidth.line}}>{r.l}</td>
                <td style={{width: colWidth.vehicle}}>{r.v}</td>
                <td style={{width: colWidth.pn}}>{r.p}</td>
                <td style={{width: colWidth.At}}>{r.iAt}</td>
                <td style={{width: colWidth.choku}}>{r.iChoku}</td>
                <td style={{width: colWidth.By}}>{r.iBy}</td>
                <td style={{width: colWidth.paletNum}}>{r.pNum}</td>
                <td style={{width: colWidth.status}}>{r.f.length > 0 ? '×' : '○'}</td>
                {
                  failureTypes2.map((ft, i) =>
                    i % 2 === 0 ?
                    <td style={{width: colWidth.failure/2}}>
                      {r.f.filter(f => f.t === ft.id).map(f => f.fQ).reduce((a,b) => {return a+b}, 0)}
                    </td> :
                    <td style={{width: colWidth.failure/2}}>
                      {
                        r.mAt ? 
                        r.f.filter(f => f.t === ft.id).map(f => f.mQ).reduce((a,b) => {return a+b}, 0) :
                        '-'
                      } 
                    </td>
                  )
                }
                <td style={{width: colWidth.comment}}>{r.fCom ? r.fCom.slice(0,4)+'..' : ''}</td>
                <td style={{width: colWidth.By}}>{r.mBy}</td>
                <td style={{width: colWidth.comment}}>{r.mCom ? r.mCom.slice(0,4)+'..' : ''}</td>
                <td style={{width: colWidth.At}}>{r.mAt}</td>
                <td style={{width: colWidth.By}}>{r.rps}</td>
                <td style={{width: colWidth.At}}>{r.pAt}</td>
                <td style={{width: colWidth.By}}>{r.pBy}</td>
              </tr>
            )
          }
          </tbody>
        </table>
      </div>
    );
  }
};

CustomTable.propTypes = {
  count: PropTypes.object,
  result: PropTypes.object,
  failureTypes: PropTypes.array,
  download: PropTypes.func
};

export default CustomTable;
