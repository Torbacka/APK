import React from "react";
import Griddle from "griddle-react";
import fakeData from "./fakeData"
import _ from 'lodash';

let externalData = fakeData.slice(0, 53);
console.log(externalData);
class ApkComponent extends React.Component {

    constructor(props, context) {
        super(props, context);

        this.state = { "results": [],
            "currentPage": 0,
            "maxPages": 0,
            "externalResultsPerPage": 5,
            "externalSortColumn":null,
            "externalSortAscending":true,
            "pretendServerData": externalData
        };
    };

    componentDidMount() {
        this.setState({
            maxPages: Math.round(this.state.pretendServerData.length/this.state.externalResultsPerPage),
            "results": this.state.pretendServerData.slice(0,this.state.externalResultsPerPage)
        })
    }
    setPage(index){
        //This should interact with the data source to get the page at the given index
        var number = index === 0 ? 0 : index * this.state.externalResultsPerPage;
        this.setState(
          {
              "results": this.state.pretendServerData.slice(number, number+5>this.state.pretendServerData.length ? this.state.pretendServerData.length : number+this.state.externalResultsPerPage),
              "currentPage": index
          });
    }
    sortData(sort, sortAscending, data){
        //sorting should generally happen wherever the data is coming from
        let sortedData = _.sortBy(data, function(item){
            return item[sort];
        });

        if(sortAscending === false){
            sortedData.reverse();
        }
        return {
            "currentPage": 0,
            "externalSortColumn": sort,
            "externalSortAscending": sortAscending,
            "pretendServerData": sortedData,
            "results": sortedData.slice(0,this.state.externalResultsPerPage)
        };
    }

    changeSort(sort, sortAscending){
        //this should change the sort for the given column
        this.setState(this.sortData(sort, sortAscending, this.state.pretendServerData));
    }
    setFilter(filter){
        //filtering should generally occur on the server (or wherever)
        //this is a lot of code for what should normally just be a method that is used to pass data back and forth
        var sortedData = this.sortData(this.state.externalSortColumn, this.state.externalSortAscending, externalData);

        if(filter === ""){
            this.setState(_.extend(sortedData, {maxPages: Math.round(sortedData.pretendServerData.length > this.state.externalResultsPerPage ? sortedData.pretendServerData.length/this.state.externalResultsPerPage : 1)}));

            return;
        }

        const filteredData = _.filter(sortedData.pretendServerData,
          function (item) {
              var arr = _.values(item);
              for (var i = 0; i < arr.length; i++) {
                  if ((arr[i] || "").toString().toLowerCase().indexOf(filter.toLowerCase()) >= 0) {
                      return true;
                  }
              }

              return false;
          });

        this.setState({
            pretendServerData: filteredData,
            maxPages: Math.round(filteredData.length > this.state.externalResultsPerPage ? filteredData.length/this.state.externalResultsPerPage : 1),
            "results": filteredData.slice(0,this.state.externalResultsPerPage)
        });
    }
    setPageSize(size){
        this.setState({
            currentPage: 0,
            externalResultsPerPage: size,
            maxPages: Math.round(this.state.pretendServerData.length > size ? this.state.pretendServerData.length/size : 1),
            results: this.state.pretendServerData.slice(0,size)
        });
    }
    render(){
        return <Griddle useExternal={true} externalSetPage={this.setPage}
                        externalChangeSort={this.changeSort} externalSetFilter={this.setFilter}
                        externalSetPageSize={this.setPageSize} externalMaxPage={this.state.maxPages}
                        externalCurrentPage={this.state.currentPage} results={this.state.results} tableClassName="table" resultsPerPage={this.state.externalResultsPerPage}
                        externalSortColumn={this.state.externalSortColumn} externalSortAscending={this.state.externalSortAscending} showFilter={true} showSettings={true} />
    }
}

export default ApkComponent;