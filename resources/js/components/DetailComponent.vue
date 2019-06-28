<template>
  <div>
    <div class="md-layout">
      <div class="md-layout-item md-size-40" style="margin:50px">
        <div style="margin-top:10px">
          <zoom-on-hover 
          :img-normal="'data:image/jpeg;base64,'+ShowedImage" 
          :scale="2"></zoom-on-hover>
          <img
            v-if="Image.truebase64 !== null"
            @click="ShowedImage = Image.truebase64"
            :src="'data:image/jpeg;base64,'+Image.truebase64"
            style="width:50px"
          >
          <img
            v-if="Image.img64x64base64 !== null"
            @click="ShowedImage = Image.img64x64base64"
            :src="'data:image/jpeg;base64,'+Image.img64x64base64"
            style="width:50px"
          >
          <img
            v-if="Image.thumbnailbase64 !== null"
            @click="ShowedImage = Image.thumbnailbase64"
            :src="'data:image/jpeg;base64,'+Image.thumbnailbase64"
            style="width:50px"
          >
        </div>
      </div>
      <div class="md-layout-item md-size-50" style="margin-top:50px;float:left">
        <div class="md-title">{{Image.title}}</div>
        <div class="md-subhead">Diupload pada {{Image.created_at}}</div>
      </div>
    </div>
  </div>
</template>

<script>
import zoomOnHover from "./ZoomComponent.vue";
export default {
  name: "DetailComponent",
  props: ["ImageId"],
  mounted() {
    this.list();
  },
  components: {
    "zoom-on-hover": zoomOnHover
  },
  data() {
    return {
      Image: null,
      loading: false,
      ShowedImage: null,
      confirmImage: false,
      deletedImage: false
    };
  },
  methods: {
    list() {
      var that = this;
      this.loading = true;
      axios
        .get("api/detail/" + that.ImageId)
        .then(response => {
          console.log(response.data);
          that.Image = response.data;
          that.ShowedImage = response.data.truebase64;
          that.loading = false;
        })
        .catch(function(error) {
          console.log(error.response.data);
          that.loading = false;
        });
    },
    deleteImage: function() {
      this.confirmImage = true;
    },
    cancelDelete: function() {
      this.confirmImage = false;
    },
    confirmDelete: function() {
      var that = this;
      axios
        .get("api//delete/" + that.ImageId)
        .then(response => {
          that.deletedImage = true;
          location.reload();
        })
        .catch(function(error) {
          console.log(error.response.data);
        });
    }
  }
};
</script>