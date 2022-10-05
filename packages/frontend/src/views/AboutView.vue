<template>
 <div class="container">
    <div class="row">
      <div class="col-12 text-left">
        <div class="about">
          <h1>All Repo Listing</h1>
          <table class="table mt-4 border text-left">
            <tr>
              <th scope="col">Id</th>
              <th scope="col"> Url</th>
            </tr>
            <tr class="border-bottom" v-for="repo in repoData" :key="repo.id">
              <td>{{ repo.id }}</td>
              <td><router-link :to="'/commintdata/'+repo.id">{{ repo.repo_url }}</router-link></td>
            </tr>
          </table>
            <p class="text-center" v-if="!repoData"><b>Not found repo data</b></p>
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
  setup() {
    
  },
   data() {
      return {
       repoData:[]
      }
    },
  mounted(){
     axios.get('http://127.0.0.1:8000/api/repourldata')
        .then((res) => {
          if (res.status == 200 ){
            this.repoData=res.data.repo_url;
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

