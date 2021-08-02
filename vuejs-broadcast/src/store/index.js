import Vue from 'vue'
import Vuex from 'vuex'
import VueCookies from 'vue-cookies'
import axios from 'axios'
import { isNil } from 'ramda';
Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    baseURL: "http://127.0.0.1:8000/api/",
    imageURL: "http://127.0.0.1:8000/image/",
    userId: "",
  },
  getters:{
    baseURL: state => state.baseURL,
    imageURL: state => state.imageURL,
    userId: state => state.userId,
  },
  mutations: {
    userId: (state, value)=> state.userId = value,
  },
  actions: {
    callApi: (context, info) => {
      const ax =  axios.create({
        baseURL: context.state.baseURL,
      });

      return new Promise((resolve, reject) => {
        const { url, params = {}, data = {}, method = 'get' } = info;
        // const withCredentials = true;
        let headers = {
          'Authorization': 'Bearer ' + VueCookies.get('accessToken'),
          'Content-Type': "application/json;charset=UTF-8"
        }
        ax.withCredentials = true;
        ax.request({ method, url, data, headers, params }).then((response) => {
          console.log('in the success');
          console.log(response.status);
          resolve(response.data);
        }).catch((error) => {
          console.log('in the error');
          console.log(error);
          if (!isNil(error.response) && error.response.status  == 401) {
            reject('unauthorized');
          }else{
            reject('others');
          }
        })
      })
    }
  },
  modules: {
  }
})
