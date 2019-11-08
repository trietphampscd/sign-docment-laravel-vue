<template>
  <div class="app flex-row">
    <div class="w-100 document-list">
      <div class="action-header">
        <h1>{{ $t('payment.document.title') }}</h1>
        <div class="action-search-field">
          <UserSelect
            v-bind:value="progress_status"
            v-bind:items="['Status', 'All', 'Completed', 'Waiting', 'Declined', 'Voided']"
            @changeValue="changeProgressStatus"
            class="mb-0 mx-1 mobile-style"
          />
          <UserSelect
            v-bind:value="period"
            v-bind:items="['Date', 'Last 24 Hours', 'Last Week', 'Last 30 Days', 'Last 6 Months', 'Custom']"
            @changeValue="changePeriod"
            class="mb-0 mx-1 mobile-style"
          />
          <b-input-group class="min-width-378px ml-1">
            <b-form-input 
            placeholder="Type here to search..."
            name="search"
            v-model="search"
            @input="filterDocuments"
            ></b-form-input>
            <b-input-group-prepend class="mr-0">
              <b-input-group-text class="h-auto">
                <i class="fa fa-search"></i>
              </b-input-group-text>
            </b-input-group-prepend>
          </b-input-group>
        </div>
      </div>
      <hr class="mb-4" />
      <div class="actions-table">
        <div class="table-header">
          <div class="d-flex align-items-center">
            <b-form-checkbox v-on:change="clickHeaderCheckbox($event)" v-model="header_checkbox" :indeterminate.sync="indeterminate"></b-form-checkbox>
            <template v-if="selected_items.length>0">
              <span>{{selected_items.length}} {{ $t('payment.document.table.selected') }}</span>
              <b-button variant="outline-primary" class="mx-3" @click="deleteDocs">{{ $t('payment.document.table.delete') }}</b-button>
            </template>
            <div class="col-basic-info comments d-mobile-none">{{ $t('payment.document.table.document') }}</div>
          </div>
          <div class="d-flex align-items-center d-mobile-none">
            <div class="col-status comments">{{ $t('payment.document.table.status') }}</div>
            <div class="col-last-change comments">{{ $t('payment.document.table.change') }}</div>
            <div class="col-action comments">{{ $t('payment.document.table.action') }}</div>
          </div>
        </div>
        <div class="table-body">
          <div v-if="doc_list.length == 0" class="table-row content-card">
            <p style="text-align: center;padding: 15px;font-size: 16px;"> Data not found </p>
          </div>
          <div v-else class="table-row content-card" v-for="(item, index) in doc_list" :key="index" @contextmenu.prevent="$refs.menu.open($event, {item: item})">
            <div class="row-content">
              <div class="d-flex align-items-center" v-bind:style="item.status == 'Voided'?{opacity: 0.4}:null">
                <b-form-checkbox v-on:change="clickCheckBox($event, index, item.id)" v-model="item.selected"></b-form-checkbox>
                <div class="col-basic-info">
                  <!-- <img :src="getFileType(item.title)" class="doc-icon" /> -->
                  <div class="ml-3">
                    <div class="doc-name">{{item.title}}</div>
                    <div class="senders comments">
                      {{item.description}}
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex align-items-center">
                <div class="col-status">
                  <div :class="getStyle(item.status)">{{item.status}}</div>
                </div>
                <div class="col-last-change comments">
                  <div class="date">{{ item.date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('DD.MM.YYYY') }}</div>
                  <div class="comments">{{ item.date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('HH:mm:ss a') }}</div>
                </div>
                <div class="col-action comments collapsed-header" v-b-toggle="'collapse'+index.toString()">
                  <span class="collapsed-icon"><i class="fa fa-caret-down " ></i></span>
                </div>
              </div>
            </div>
            <b-collapse :id="'collapse'+index.toString()" class="row-body">
              <div class="wrap-content" v-for="(recip, index) in item.recipients" :key="index">
                <div class="row-detail add-margin-left">
                  <div class="d-flex align-items-center">
                    <div class="col-basic-info remove-margin-left">
                      <img :src="recip.avatar" style="width: 33px; height: 33px; border-radius: 50%" />
                      <div class="ml-3">
                        <div class="user-name">{{recip.name}}</div>
                        <div class="comments">Sign status: {{getStatusRecipients(recip.action)}}</div>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex align-items-center">
                    <div class="col-status">
                      <div :class="getStyleRecipients(recip.status)">{{recip.status}}</div>
                    </div>
                    <div class="col-last-change">
                      <div class="date">{{ recip.date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('DD.MM.YYYY') }}</div>
                      <div class="comments">{{ recip.date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('HH:mm:ss a') }}</div>
                    </div>
                    <div class="col-action comments"></div>
                  </div>
                </div>
              </div>
            </b-collapse>
          </div>
          
        </div>
      </div>
      <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
          <span class="comments mr-3">Per page</span>
          <div>
            <span class="mr-3 clickable-icon" v-for="(item, index) in pages" :key="index" 
              v-bind:class="item == items_per_page?'selected':'comments'"
              v-on:click ="items_per_page = item"
            >
              {{item}}
            </span>
          </div>
        </div>

        <b-pagination align="right" :total-rows="totalPage" v-model="currentPage" :per-page="items_per_page" @input="getPostData(currentPage)"></b-pagination>
      </div>
    </div>
    <b-modal id="share-modal" ref="share-modal"
       hide-footer centered size="lg">
      <div class="share-modal" v-if="selected_template">
        <div class="title">Do you want to share the XXX template with other users?</div>
        <b-form-group>
          <b-input-group>
            <b-input-group-prepend>
              <b-input-group-text>
                <!-- <i class="fa fa-lock"></i> -->
                <UserIcon icon="file_name.svg" class="mr-2"/>
              </b-input-group-text>
            </b-input-group-prepend>
            <b-form-input
              type="text"
              placeholder="Input file name"
              v-model="selected_template.fileName"
            ></b-form-input>
          </b-input-group>
        </b-form-group>
        <div class="doc-container mb-2">
          <div class="doc-tag" v-for="(item, index) in tags" :key="index">
            {{item}} <i class="fa fa-close ml-1 clickable-icon" v-on:click="tags.splice(index,1);"/>
          </div>
        </div>
        
        <b-form-group>
          <b-input-group>
            <b-input-group-prepend>
              <b-input-group-text>
                <i class="fa fa-tag"/>
              </b-input-group-text>
            </b-input-group-prepend>
            <b-form-input
              type="text"
              placeholder="Input Tag name and click 'Enter'"
              v-model="tag_name"
              v-on:keyup.enter="tags.push(tag_name); tag_name='';"
            ></b-form-input>
          </b-input-group>
        </b-form-group>
        <div class="d-flex flex-wrap mb-4">
          <div class="proposed-tag" v-for="(item, index) in proposed_tag" :key="index">
            {{item}}
          </div>
        </div>
        <div class="text-center">
          <button type="submit" class="btn btn-primary" v-on:click="shareTemplate()">Share this template</button>
        </div>
      </div>
    </b-modal>
    <vue-context ref="menu" class="context-menu">
      <template slot-scope="child">
        <li><a href="#" @click.prevent="onClick($event, child.data)"><i class="fa fa-pencil mr-2 clickable-icon"/>Sign</a></li>
        <li><a href="#" @click.prevent="onClick($event, child.data)"><i class="fa fa-copy mr-2 clickable-icon"/>Create a copy</a></li>
        <li><a href="#" @click.prevent="openShareModal(child.data)"> <i class="fa fa-download mr-2 clickable-icon"/>Save as template</a></li>
        <li><a href="#" @click.prevent="onClick($event, child.data)"><i class="fa fa-clock-o mr-2 clickable-icon"/>History</a></li>
        <li><a href="#" @click.prevent="onClick($event, child.data)"><i class="fa fa-file-excel-o mr-2 clickable-icon"/>Export as CSV</a></li>
        <li><a href="#" @click.prevent="onClick($event, child.data)"><i class="fa fa-trash mr-2 clickable-icon"/>Delete</a></li>
      </template>
    </vue-context>
  </div>
</template>

<script>
import UserIcon from "../../components/UserIcon";
import UserSelect from "../../components/UserSelect";
import { VueContext } from 'vue-context';
import store from '../../store/store'
import { mapGetters } from "vuex";
import {
  GET_LIST_DOCUMENTS_REQUEST,
  REMOVE_DOCUMENT_DETAIL_REQUEST
} from "../../store/modules/document";

export default {
  name: "DocumentList",
  components: {
    UserIcon,
    UserSelect,
    VueContext
  },
  data() {
    return {
      header_checkbox: false,
      indeterminate: false,
      selected_items: [],
      selected_id_items: [],
      items_per_page: 10,
      pages: [10, 25, 50, 100],
      tag_name: "",
      selected_template: null,
      tags: ["Inquiry", "Report", "Tag #3", "Tag #4", "Tag #5", "Tag 6", "Report", "Tag #3", "Tag #4"],
      proposed_tag: ["Proposed tag", "Example proposed tag", "Proposed tag"],
      doc_list: [],
      doc_list_search: [],
      currentPage: 1,
      totalPage: null,
      period: "Date",
      progress_status: "Status",
      actions: [
        {
          setted: false,
          fileName: "Continuous Improvement.pdf"
        }
      ],
      search: ''
    };
  },
  created(){
    this.getPostData()
  },
  mounted() {
  },
  filters: {
    date: function (date) {
      return moment(date).format('DD.MM.YYYY');
    },
    time: function (date) {
      return moment(date).format('h:mm a');
    },
    moment: function (date) {
      return moment(date).format('MMMM Do YYYY, h:mm:ss a');
    }
  },
  computed: {
    ...mapGetters(['getListDocs'])
  },
  methods: {
    filterDocuments() {
      // this.filteredList()
      this.getPostData()
      this.totalPage = this.doc_list.length
    },
    filteredList() {
      let vm = this;
      let doc_list_search =  this.doc_list_search
      if(this.search !== ''){
        this.doc_list = doc_list_search.filter(item => item.title.toLowerCase().indexOf(vm.search.toLowerCase()) > -1)
      }else{
        this.doc_list = this.doc_list_search
      }
    },
    getPostData() {
      store.dispatch(GET_LIST_DOCUMENTS_REQUEST, {per_page: this.items_per_page, page: this.currentPage, date: this.period, status: this.progress_status, search: this.search})
      .then( res => {
        this.doc_list = this.convertListDocs(this.getListDocs);
        this.doc_list_search = this.convertListDocs(this.getListDocs);
      })
      .catch( err => {
        console.log('err---------', err)
      });
    },
    convertListDocs(list){
      this.totalPage = list.total;
      let data = {};
      const list_docs = list.data.map((value, key) => {
        this.convertDescription(value.recipients)
        return {...data, id: value.document_id, selected: false, title: value.name, status: value.status, description: this.convertDescription(value.recipients), recipients: this.convertRecipients(value.recipients), date: value.updated_at}
      })
      return list_docs.sort(function (a, b) {
        return new Date(b.date) - new Date(a.date);
      });
      // this.doc_list = list_docs.sort(function (a, b) {
      //   return new Date(b.date) - new Date(a.date);
      // });
    },
    convertRecipients(list){
      let data = {};
      const list_docs = list.map((value, key) => {
        return {...data, name: value.name, status: value.status == 0 ? 'Waiting to sign' : 'Completed', action: value.action, avatar: value.user && value.user.avatar ? value.user.avatar : 'img/avatars/default.png', date: value.updated_at}
      })
      return list_docs;
    },
    convertDescription(list){
      const name = []; 
      list.map((value, key) => {
        name.push(value.name);
      })
      return name.join(', ');
    },
    filterDocs(list, value)
    {
      const data = list.filter( v => {
        return v.document_id === value;
      })
    },
    removeDuplicates(originalArray, prop) {
      var newArray = [];
      var lookupObject  = {};

      for(var i in originalArray) {
        lookupObject[originalArray[i][prop]] = originalArray[i];
      }

      for(i in lookupObject) {
         newArray.push(lookupObject[i]);
      }
      return newArray;
    },
    getStyle(status){
      if(status == 'completed') {
        return "status completed"
      } else if(status == 'waiting') {
        return "status waiting"
      } else if(status == 'Rejected') {
        return "status rejected"
      } else if(status == 'Voided') {
        return "status voided"
      } 
      return "status completed";
    },
    getStyleRecipients(status){
      if(status == 'Completed') {
        return "status completed"
      } else if(status == 'Waiting to sign') {
        return "status waiting"
      }
      return "status completed";
    },
    getStatusRecipients(status){
      if(status == 'sign') {
        return "Needs to Sign"
      } else if(status == 'signer') {
        return "In personal signer"
      } else if(status == 'copy') {
        return "Receives a Copy"
      }
    },
    shareTemplate() {

    },
    openShareModal(item) {
      this.selected_template = item;
      this.$refs['share-modal'].show();
    },
    clickHeaderCheckbox($event) {
      this.selected_items = [];
      this.selected_id_items = [];
      this.doc_list.forEach((item, index) => {
        item.selected = $event;
        if($event) {
          this.selected_items.push(index);
          this.selected_id_items.push(item.id);
        } 
      });
    },
    clickCheckBox($event, index, id) {
      const pos = this.selected_items.indexOf(index);
      if($event) {
        if(pos<0) {
          this.selected_items.push(index);
          this.selected_id_items.push(id);
        } 
      } else {
        if(pos>=0){
          this.selected_items.splice(pos, 1);
          this.selected_id_items.splice(pos, 1);
        }
      }
      if(this.selected_items.length>0) {
        
        if(this.selected_items.length == this.doc_list.length){
          this.header_checkbox = true;
          this.indeterminate = false;
        } else {
          this.indeterminate = true;
        }
      } else {
        this.indeterminate = false;
        this.header_checkbox = false;
      }
    },
    onClick(event, data) {
      console.log(data);
    },
    getFileType(fileName) {
      return "img/icons/" + fileName.split(".").pop() + ".svg";
    },
    changePeriod(e) {
      this.period = e;
      this.getPostData();
    },
    changeProgressStatus(e) {
      this.progress_status = e;
      this.getPostData();
    },
    deleteDocs() {
      store.dispatch(REMOVE_DOCUMENT_DETAIL_REQUEST, {document_id: this.selected_id_items})
      .then( res => {
        res && res.status && this.getPostData();
        this.selected_items = [];
        this.header_checkbox = false;
        this.indeterminate = false;
        
      })
      .catch( err => {
        console.log('err---------', err)
      });
    }
  }
};
</script>

<style lang="scss">
@import "./DocumentList.scss";
</style>

