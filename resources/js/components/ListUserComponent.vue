<template>
  <v-app>
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
          <td>{{ props.item.name }}</td>
          <td>{{ props.item.email }}</td>
          <td>{{ props.item.secretid }}</td>
          <td>{{ props.item.secret }}</td>
          <td>
            <v-tooltip top v-if="props.item.id==saya.id">
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="primary" v-on="on">
                  <v-icon dark>accessibility</v-icon>
                </v-btn>
              </template>
              <span>Saya</span>
            </v-tooltip>
            <v-tooltip top v-else-if="props.item.admin==1">
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="primary" @click="toAdmin(props.item.id)" v-on="on">
                  <v-icon dark>done</v-icon>
                </v-btn>
              </template>
              <span>Admin</span>
            </v-tooltip>
            <v-tooltip top v-else>
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="error" @click="toAdmin(props.item.id)" v-on="on">
                  <v-icon dark>close</v-icon>
                </v-btn>
              </template>
              <span>Bukan Admin</span>
            </v-tooltip>
            <v-tooltip top v-if="props.item.id==saya.id">
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="primary" v-on="on">
                  <v-icon dark>accessibility</v-icon>
                </v-btn>
              </template>
              <span>Saya</span>
            </v-tooltip>
            <v-tooltip top v-else-if="props.item.active==1">
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="primary" @click="toActive(props.item.id)" v-on="on">
                  <v-icon dark>done</v-icon>
                </v-btn>
              </template>
              <span>Aktif</span>
            </v-tooltip>
            <v-tooltip top v-else>
              <template v-slot:activator="{ on }">
                <v-btn fab dark small color="error" @click="toActive(props.item.id)" v-on="on">
                  <v-icon dark>close</v-icon>
                </v-btn>
              </template>
              <span>Tidak Aktif</span>
            </v-tooltip>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-btn
                  fab
                  dark
                  small
                  color="orange"
                  @click="generateToken(props.item.id)"
                  v-on="on"
                >
                  <v-icon dark>vpn_key</v-icon>
                </v-btn>
              </template>
              <span>Generate Token</span>
            </v-tooltip>
          </td>
        </template>
      </v-data-table>
    </v-card>
  </v-app>
</template>

<script>
export default {
  name: "ListUserComponent",
  mounted() {
    this.list();
  },
  data() {
    return {
      headers: [
        { text: "Nama Pengguna", value: "name" },
        { text: "Email", value: "email" },
        { text: "Secret ID", value: "secretid" },
        { text: "Secret", value: "secret" },
        { text: "Action", value: "id" }
      ],
      rowsPerPageItems: [25, 50, 75, 100],
      pagination: {
        rowsPerPage: 25
      },
      saya: null,
      users: []
    };
  },
  methods: {
    generateToken(id) {
      var that = this;
      axios
        .get("api/api/generateToken/" + id)
        .then(function(response) {
          that.list();
          console.log(response.data);
        })
        .catch(function(response) {
          console.log(response.data);
        });
    },
    toAdmin(id) {
      var that = this;
      axios
        .get("api/toAdmin/" + id)
        .then(function(response) {
          that.list();
          console.log(response.data);
        })
        .catch(function(response) {
          console.log(response.data);
        });
    },
    toActive(id) {
      var that = this;
      axios
        .get("api/toActive/" + id)
        .then(function(response) {
          that.list();
          console.log(response.data);
        })
        .catch(function(response) {
          console.log(response.data);
        });
    },
    list() {
      var that = this;
      axios
        .get("api/listuser")
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
    }
  }
};
</script>