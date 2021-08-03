<template>
  <div class="container-1200">
    <div class="enkaizenBg t-text-white t-w-100 t-mt-8">
      <p class="t-text-center t-py-2 t-my-0">Gallery</p>
    </div>

    <div class="t-flex t-justify-between">
      <div><v-btn color="error" @click="signout"> Logout </v-btn></div>
      <div>
        <v-dialog v-model="dialog" width="500">
          <template v-slot:activator="{ on, attrs }">
            <v-btn color="success" class="t-mb-2" v-bind="attrs" v-on="on">
              ADD MORE
            </v-btn>
          </template>

          <v-card>
            <v-form ref="form" @submit.prevent="submit">
              <v-card-title class="text-h5 grey lighten-2">
                ADD A NEW PHOTO
              </v-card-title>

              <div class="t-flex t-justify-center t-my-4 t-px-4">
                <v-text-field
                  value=""
                  label="Link"
                  clearable
                  outlined
                  v-model="link"
                  type="text"
                  :rules="[
                    (v) => !!v || 'Link is required',
                    (v) =>
                      /^(http|https){1}(:\/\/){1}[\S]*(.jpg|.png){1}$/.test(
                        v
                      ) ||
                      'Url of png and jpg are allowed ex. http://abc.com/a.png',
                  ]"
                  style="width: 300px"
                ></v-text-field>
              </div>

              <v-divider></v-divider>

              <v-card-actions>
                <v-btn
                  color="error"
                  text
                  @click="
                    () => {
                      dialog = false;
                      this.$refs.form.reset();
                    }
                  "
                >
                  CLOSE
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn color="primary" text type="submit" :loading="loading">
                  SUBMIT
                </v-btn>
              </v-card-actions>
            </v-form>
          </v-card>
        </v-dialog>
      </div>
    </div>

    <div class="t-grid t-grid-cols-4 t-gap-3 t-mb-8">
      <div
        class="t-cursor-pointer t-h-48 t-bg-green-200 t-overflow-hidden"
        v-for="photo in photos"
        :key="photo.id"
      >
        <v-img
          aspect-ratio="1"
          :src="`${imageURL + photo.user_id}/${photo.path}.jpg`"
          alt="img"
        />
      </div>
    </div>

    <v-snackbar
      v-model="snackbar"
      :color="color"
      absolute
      center
      rounded="pill"
      timeout="-1"
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
// import axios from 'axios';
import Pusher from "pusher-js"; // import Pusher
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      photos: [],
      pusher: null,
      dialog: false,
      link: "",
      loading: false,
      apiUrl: "images",
      text: "",
      snackbar: false,
      color: "",
    };
  },
  computed: {
    ...mapGetters(["imageURL", "userId"]),
  },
  methods: {
    submit() {
      if (!this.$refs.form.validate()) return;
      this.loading = true;

      this.$store
        .dispatch("callApi", {
          url: this.apiUrl,
          method: "post",
          data: { path: this.link },
        })
        .then((response) => {
          this.text = response;
          this.$refs.form.reset();
          this.color = "success";
          this.snackbar = true;
          this.dialog = false;
        })
        .catch((error) => {
          this.text = "Server Error";
          this.color = "error";
          this.snackbar = true;
          if (error == "unauthorized") {
            this.signout();
          }
        })
        .finally(() => (this.loading = false));
    },
    signout() {
      this.$cookies.keys().forEach((cookie) => this.$cookies.remove(cookie));
      this.$router.push("/");
    },
  },
  mounted() {
    this.$store
      .dispatch("callApi", {
        url: this.apiUrl,
      })
      .then((response) => {
        this.photos = response;
      })
      .catch((error) => {
        if (error == "unauthorized") {
          this.signout();
        }
      });
  },
  created() {
    this.pusher = new Pusher("1590530dcf8b3b74448e", {
      cluster: "ap2",
    });
    this.pusher.subscribe("DownloadPhoto"+this.userId);
    this.pusher.bind("PhotoDownload", (data) => {
        if (data.link == "failed") {
        this.color = "error";
        this.text = "There is some problem in the link";
        this.snackbar = true;
      }else{
        this.photos.push({ user_id: this.userId, path: data.link });
        this.color = "success";
        this.text = "Gallery has been updated";
        this.snackbar = true;
      }
    });
  },
  destroyed(){
      this.pusher.unsubscribe("DownloadPhoto"+this.userId);
  }
};
</script>

<style lang="scss" scoped>
</style>
