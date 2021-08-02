<template>
  <div>
    <v-form ref="form" @submit.prevent="submit">
      <div class="t-grid t-justify-center t-h-screen t-w-screen t-items-center">
        <div>
          <div class="t-text-center t-p-4 enkaizenBg t-text-white t-mb-4">
            {{ titleText }}
          </div>
          <div>
            <v-text-field
              value=""
              label="Email"
              outlined
              v-model="fields.email"
              type="text"
              :rules="[
                (v) => !!v || 'E-mail is required',
                (v) => /.+@.+/.test(v) || 'E-mail must be valid',
              ]"
              style="width: 300px"
            ></v-text-field>
          </div>
          <div>
            <v-text-field
              value=""
              label="Password"
              v-model="fields.password"
              outlined
              type="password"
              style="width: 300px"
              :rules="[(v) => !!v || 'Password is required']"
            ></v-text-field>
          </div>
          <div>
            <v-btn
              :loading="loading"
              type="submit"
              block
              color="success"
              class="t-mb-2"
            >
              {{ btnText }}
            </v-btn>
          </div>
          <div>
            <v-btn
              @click="changeOpt"
              block
              color="secondary"
              v-text="'Login/Register'"
            >
            </v-btn>
          </div>
        </div>
      </div>
    </v-form>

    <v-snackbar
      v-model="snackbar"
      color="blue-grey"
      absolute
      center
      rounded="pill"
      top
    >
      {{ text }}

      <template v-slot:action="{ attrs }">
        <v-btn color="white" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
import { isNil , isEmpty } from 'ramda';
export default {
  data() {
    return {
      fields: {
        email: "",
        password: "",
      },
      loading: false,
      snackbar: false,
      text: "",
      titleText: "Login to Enkaizen",
      apiUrl: "signin",
      btnText: "Login",
    };
  },
  methods: {
    changeOpt() {
      this.btnText = this.btnText == "Login" ? "Register" : "Login";
      this.apiUrl = this.apiUrl == "signup" ? "signin" : "signup";
      this.titleText =
        this.titleText == "Signup to Enkaizen"
          ? "Login to Enkaizen"
          : "Signup to Enkaizen";
    },
    submit() {
      if (!this.$refs.form.validate()) return;
      this.loading = true;
      console.log;
      this.$store
        .dispatch("callApi", {
          url: this.apiUrl,
          data: this.fields,
          method: "post",
        })
        .then((response) => {
          this.$cookies.set("accessToken", response.accessToken);
          this.$cookies.set("userId", response.user.id);
          this.$store.commit('userId' , response.user.id);
          this.text = "successful";
          this.snackbar = true;
          this.$router.push("Gallery");
        })
        .catch(() => {
          this.text = "invalid credentials";
          this.snackbar = true;
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
  mounted() {
    // this.$cookies.keys().forEach((cookie) => this.$cookies.remove(cookie));
    if( !isNil( this.$cookies.get("accessToken")) && !isEmpty(this.$cookies.get("accessToken")) ){
        this.$router.push('Gallery');
    }
  },
};
</script>

<style lang="scss" scoped>
</style>