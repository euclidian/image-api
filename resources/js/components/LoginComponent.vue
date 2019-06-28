<template>
  <div class="centered-container">
    <md-content class="md-elevation-3">

      <div class="title">
        <div class="md-title">Image <b>API</b></div>
        <div class="md-body-1">Image Uploader API</div>
      </div>
      <div class="form">
        <md-field>
          <label>Email</label>
          <md-input name="email" v-model="email" autofocus></md-input>
        </md-field>

        <md-field md-has-password>
          <label>Password</label>
          <md-input name="password" v-model="password" type="password"></md-input>
          <span class="md-error" v-if="gagal">Email atau Password salah</span>
        </md-field>
      </div>

      <div class="actions md-layout md-alignment-center-space-between">
        <md-button type="submit" class="md-raised md-primary" @click="auth">Log in</md-button>
      </div>

      <div class="loading-overlay" v-if="loading">
        <md-progress-spinner md-mode="indeterminate" :md-stroke="2"></md-progress-spinner>
      </div>
    </md-content>
    <md-dialog-alert
    :md-active.sync="gagal"
    md-content="Email atau Password Salah !"
    md-confirm-text="Tutup" />
  </div>
</template>

<script>
export default {
  name: "LoginComponent",
  data() {
    return {
      gagal:false,
      loading: false,
      email:null,
      password:null,
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
  },
  methods: {
    auth() {
      var that = this;
      this.loading = true;
      axios
      .post("login",{
        "_token"   : that.csrf,
        "email" : that.email,
        "password" : that.password
      })
      .then(response => {
        window.location.replace("/home");
        that.loading = false;
      })
      .catch(function(error) {
        console.log(error.response.data);
        that.gagal    = true;
        that.password = null;
        that.email    = null
        that.loading  = false;
      });
    }
  }
};
</script>

<style lang="scss">
.centered-container {
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  height: 100vh;
  .title {
    text-align: center;
    margin-bottom: 30px;
    img {
      margin-bottom: 16px;
      max-width: 80px;
    }
  }
  .actions {
    .md-button {
      margin: 0;
    }
  }
  .form {
    margin-bottom: 60px;
  }
  .background {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
    z-index: 0;
  }
  .md-content {
    z-index: 1;
    padding: 40px;
    width: 100%;
    max-width: 400px;
    position: relative;
  }
  .loading-overlay {
    z-index: 10;
    top: 0;
    left: 0;
    right: 0;
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
  }
}
</style>