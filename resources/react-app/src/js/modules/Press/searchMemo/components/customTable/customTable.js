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
      createdAt: 128,
      line: 50,
      vehicle: 44,
      pn: 100,
      capacity: 50,
      createdBy: 80,
      failure: 88,
      comment: 78
    };

    let tableWidth = colWidth.number + colWidth.vehicle + colWidth.pn + colWidth.name + colWidth.panelId + colWidth.choku + colWidth.inspectedBy + colWidth.updatedBy + colWidth.status + colWidth.comment + colWidth.inspectedAt + colWidth.updatedAt + 18;
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
              <th rowSpan="2" style={{width: colWidth.number}}>No.</th>
              <th
                rowSpan="2"
                style={{width: colWidth.createdAt}}
                className={`clickable ${sort.key == 'createdAt' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'createdAt') this.setState({sort: { key: 'createdAt', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'createdAt', asc: true, id: 0 }});
                }}
              >
                登録日時
              </th>
              <th rowSpan="2" style={{width: colWidth.line}}>ライン</th>
              <th rowSpan="2" style={{width: colWidth.vehicle}}>車種</th>
              <th rowSpan="2" style={{width: colWidth.pn}}>品番</th>
              <th rowSpan="2" style={{width: colWidth.capacity}}>収容数</th>
              <th
                rowSpan="3"
                style={{width: colWidth.createdBy}}
                className={`clickable ${sort.key == 'createdBy' ? sort.asc ? 'asc' : 'desc' : ''}`}
                onClick={() => {
                  if(sort.key == 'createdBy') this.setState({sort: { key: 'createdBy', asc: !sort.asc, id: 0 }});
                  else this.setState({sort: { key: 'createdBy', asc: true, id: 0 }});
                }}
              >
                検査者
              </th>
              {
                failureTypes.length > 0 &&
                <th colSpan={failureTypes.length}>手直内訳</th>
              }
              <th rowSpan="3" style={{width: colWidth.comment}}>コメント</th>
            </tr>
            <tr>
            {
              failureTypes.map(f =>
                <th
                  style={{width: colWidth.failure}}
                  colSpan={1}
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
          </thead>
          <tbody>
          {
            result.map((r, i) =>
              <tr>
                <td style={{width: colWidth.number}}>{i+1}</td>
                <td style={{width: colWidth.createdAt}}>{r.cAt}</td>
                <td style={{width: colWidth.line}}>{r.l}</td>
                <td style={{width: colWidth.vehicle}}>{r.v}</td>
                <td style={{width: colWidth.pn}}>{r.p}</td>
                <td style={{width: colWidth.capacity}}>{r.cap}</td>
                <td style={{width: colWidth.createdBy}}>{r.cBy}</td>
                {
                  failureTypes.map((ft, i) =>
                    <td style={{width: colWidth.failure}}>
                      {
                        r.f.filter(f => f.t === ft.id).map(f => 
                          <p style={{fontSize: 10}}>{`${f.first} ~ ${f.last}`}</p>
                        )}
                    </td>
                  )
                }
                <td style={{width: colWidth.comment}}>{r.com}</td>
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
