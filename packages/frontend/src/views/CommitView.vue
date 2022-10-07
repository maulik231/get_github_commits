<template>
  <div class="container">
    <div class="row">
      <div class="col-12 text-left">
        <div class="about">
          <h1>Commit Data</h1>
          <table class="table   border text-left">
            <tr>
              <th>id</th>
              <th>name</th>
              <th>email</th>
              <th>date</th>
              <th>repo id</th>
              <th>message</th>
            </tr>
            <tr v-for="commit in commitdatas" :key="commit.id">
              <td>{{ commit.commit_id }}</td>
              <td>{{ commit.committer_name }}</td>
              <td>{{ commit.committer_email }}</td>
              <td>{{ commit.committer_date }}</td>
              <td>{{ commit.repo_id }}</td>
              <td>{{ commit.commit_message }}</td>
            </tr>
          </table>
            <p class="text-center" v-if="!commitdatas"><b>Not found data</b></p>
          <nav aria-label="Page navigation example">
            <ul class="pagination" v-if="nexturl || prevurl"  >
              <li class="page-item"><a  style="cursor: pointer;" class="page-link" @click="prev(prevurl)">Previous</a></li>
              <li class="page-item"><a  style="cursor: pointer;" class="page-link"  @click="next(nexturl)">Next</a></li>
            </ul> 
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue';
import axios from 'axios';
import 'vue-toast-notification/dist/theme-sugar.css';
import VueToast from "vue-toast-notification";
Vue.use(VueToast);
export default {
  name: "CommitView",
   data() {
      return {
       commitdatas:[

       ],
       nexturl:"",
       prevurl:'',
       repo_url:'',
      }
    },
    methods:{
    prev(data){
        if(data){
            axios.get(data)
            .then((res) => {
            if (res.status == 200){
                this.commitdatas=res.data.commit_data.data;
                this.nexturl=res.data.commit_data.next_page_url;
                this.prevurl=res.data.commit_data.prev_page_url;
            }
            })
        }
    },
    next(data){
        if(data){
             axios.get(data)
            .then((res) => {
            if (res.status == 200){
                this.commitdatas=res.data.commit_data.data;
                this.nexturl=res.data.commit_data.next_page_url;
                this.prevurl=res.data.commit_data.prev_page_url;
            }
            })
        }
    }        
    },
  mounted(){
     this.repo_url=  parseInt(
      window.location.href.split("/")[
        window.location.href.split("/").length - 1
      ]
    );
     axios.get('http://127.0.0.1:8000/api/commitdatas/'+this.repo_url)
        .then((res) => {
          if (res.status == 200){
            this.commitdatas=res.data.commit_data.data;
            this.nexturl=res.data.commit_data.next_page_url;
            this.prevurl=res.data.commit_data.prev_page_url;
          }
        })
        .catch((error) => {
           if (error.response.status == 422) {
             Vue.$toast.open({message: error.response.data.errors,
              type: 'error',
            });
            } else {
            Vue.$toast.open({message: "Something went wrong please reload the page",
              type: 'error',
            });
            }
        })
  },
}
</script>

