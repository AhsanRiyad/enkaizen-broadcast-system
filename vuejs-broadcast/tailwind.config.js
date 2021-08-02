module.exports = {
  purge: {
    enabled: true,
    content: [
      // './src/views/authenitcations/signIn.vue',
      './src/views/auth/signin.vue',
      './src/views/gallery/photoGallery.vue'
  ],
  },
  prefix: 't-',
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
