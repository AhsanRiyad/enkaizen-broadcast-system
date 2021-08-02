<template>
  <v-app>
   <router-view></router-view>
  </v-app>
</template>

<script>
import { isNil } from 'ramda'
export default {
  name: 'App',
  mounted(){
      if(!isNil(this.$cookies.get("userId"))){
        this.$store.commit('userId' , this.$cookies.get("userId"));
      }
  }
};
</script>
