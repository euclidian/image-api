<template>
  <v-app>
    <v-btn @click="addimage = true" fixed dark fab bottom right color="blue">
      <v-icon>add</v-icon>
    </v-btn>
    <v-snackbar v-model="snackbar" :bottom="true" :right="true" :timeout="4000">
      Data Gambar Diperbaharui
      <v-btn color="pink" flat @click="snackbar = false">Tutup</v-btn>
    </v-snackbar>
    <v-dialog v-model="detailMode" fullscreen hide-overlay transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click="detailMode= false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>Detail Gambar</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat @click="deleteImage(ImageId)">
              <v-icon dark>delete</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <detail-component v-if="detailMode" :ImageId="ImageId"></detail-component>
      </v-card>
    </v-dialog>
    <v-dialog v-model="isDelete" persistent max-width="290">
      <v-card>
        <v-card-title class="headline">Apakah anda yakin?</v-card-title>
        <v-card-text>Data yang terhapus tidak akan dikembalikan.</v-card-text>
        <v-card-text v-if="loading">
          <div class="text-xs-center">
            <v-progress-circular indeterminate color="primary"></v-progress-circular>
          </div>
        </v-card-text>
        <v-card-actions v-if="!loading">
          <v-spacer></v-spacer>
          <v-btn color="error" flat @click="isDelete = false">Batal</v-btn>
          <v-btn color="error" @click="deleted">Hapus</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="addimage" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">Upload Gambar</span>
        </v-card-title>
        <v-card-text v-if="loading">
          <div class="text-xs-center">
            <v-progress-circular indeterminate color="primary"></v-progress-circular>
          </div>
        </v-card-text>
        <v-card-text v-else>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12>
                <input
                  ref="inputUpload"
                  type="file"
                  accept="image/jpeg, image/png"
                  @change="handleFileUpload"
                >
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions v-if="!loading">
          <v-spacer></v-spacer>
          <v-btn color="error" flat @click="addimage = false">Batal</v-btn>
          <v-btn color="primary" flat @click="save" v-if="ImageFile != null ">Simpan</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card>
      <v-card-title primary-title>
        <div>
          <h3 class="headline mb-0">Daftar Pengguna</h3>
        </div>
      </v-card-title>
      <v-data-table
        :rows-per-page-items="rowsPerPageItems"
        :pagination.sync="pagination"
        :headers="headers"
        :items="users"
        class="elevation-1"
      >
        <template v-slot:items="props">
          <td>
            <img
              v-if="props.item.image64 !== null"
              :src="'data:image/jpeg;base64,'+props.item.image64"
            >
            <span v-else>Gambar tidak ada</span>
          </td>
          <td>{{ props.item.title }}</td>
          <td>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="primary" @click="detailImage(props.item.id)" v-on="on">
                  <v-icon dark>info</v-icon>
                </v-btn>
              </template>
              <span>Detail Gambar</span>
            </v-tooltip>
            <a :href="props.item.cdn">
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-btn fab dark small color="success" v-on="on">
                    <v-icon dark>save_alt</v-icon>
                  </v-btn>
                </template>
                <span>Download Gambar</span>
              </v-tooltip>
            </a>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="error" @click="deleteImage(props.item.id)" v-on="on">
                  <v-icon dark>delete</v-icon>
                </v-btn>
              </template>
              <span>Hapus Gambar</span>
            </v-tooltip>
          </td>
        </template>
      </v-data-table>
    </v-card>
  </v-app>
</template>

<script>
import DetailComponent from "./DetailComponent.vue";
export default {
  name: "UserComponent",
  components: {
    "detail-component": DetailComponent
  },
  mounted() {
    this.list();
  },
  data() {
    return {
      headers: [
        { text: "Image", value: "image64" },
        { text: "Old Name", value: "title" },
        { text: "Actions", value: "id" }
      ],
      rowsPerPageItems: [25, 50, 75, 100],
      pagination: {
        rowsPerPage: 25
      },
      saya: null,
      users: [],
      ImageFile: null,
      ImageId: null,
      isDelete: false,
      addimage: false,
      loading: false,
      snackbar: false,
      detailMode: false
    };
  },
  methods: {
    detailImage(id) {
      this.ImageId = id;
      console.log(this.ImageId);
      this.detailMode = true;
    },
    handleFileUpload(e) {
      this.ImageFile = e.target.files[0];
      console.log(this.ImageFile);
    },
    deleteImage: function(id) {
      this.ImageId = id;
      this.isDelete = true;
    },
    deleted() {
      var that = this;
      axios
        .get("api/delete/" + this.ImageId)
        .then(function() {
          that.isDelete = false;
          that.snackbar = true;
          that.loading = false;
          that.detailMode = false;
          that.list();
        })
        .catch(function(error) {
          console.log(error.response.data);
          that.addtemplate = false;
        });
    },
    list() {
      var that = this;
      axios
        .get("api/list")
        .then(function(response) {
          that.users = response.data.data;
          console.log(response.data);
        })
        .catch(function(response) {
          console.log(response.data);
        });
      axios
        .get("api/getUser")
        .then(function(response) {
          that.saya = response.data;
          console.log(response.data);
        })
        .catch(function(response) {
          console.log(response.data);
        });
    },
    save: function() {
      let formData = new FormData();
      var that = this;
      that.loading = true;
      formData.append("image", this.ImageFile);
      axios
        .post("api/upload", formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(function() {
          that.addimage = false;
          that.snackbar = true;
          that.loading = false;
          that.list();
        })
        .catch(function(error) {
          console.log(error.response.data);
        });
    }
  }
};
</script>