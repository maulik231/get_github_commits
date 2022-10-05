<template>
  <div class="container">
    <div class="row">
      <div class="col-12 text-left">
        <b-form @submit="onSubmit" @reset="onReset">

          <b-form-group id="input-group-2" label="Your Repo URL:" label-for="input-2" description="please enter only public repo URL">
            <b-form-input
              id="input-2"
              v-model="formData.repo_url"
              placeholder="Enter repo url"
              
            ></b-form-input>
          </b-form-group>

          <b-button type="submit" class="m-2" variant="primary">Submit</b-button>
          <b-button type="reset" variant="danger">Reset</b-button>
        </b-form>
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
    data() {
      return {
        formData:{
          repo_url: '',
        }
      }
    },
    methods: {
      onSubmit(event) {
        event.preventDefault();
        axios.post('http://127.0.0.1:8000/api/repo-url',this.formData)
        .then((res) => {
          console.log(res)
          if (res.status == 200){
            this.formData.repo_url = '';
            this.$router.push("about");
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
      onReset(event) {
        event.preventDefault();
        this.formData.repo_url = '';
      }
    }
  }
</script>