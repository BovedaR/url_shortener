<template>
  <div class="url-form">
    <h2>{{ formTitle }}</h2>
    <form @submit.prevent="submit">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" v-model="url.name" required>
      </div>
      <div class="form-group">
        <label for="originalUrl">Original URL:</label>
        <input type="text" id="originalUrl" v-model="url.originalUrl" required>
      </div>
      <div class="form-group">
        <label for="shortUrl">Short URL:</label>
        <div class="short-url-input">
          <input type="text" id="shortUrl" v-model="url.shortUrl" readonly>
          <button type="button" @click="refreshShortUrl">Refresh</button>
        </div>
      </div>
      <div class="form-group">
        <label for="collection">Collection:</label>
        <select id="collection" v-model="url.collectionId">
          <option value="">None</option>
          <option v-for="collection in collections" :key="collection.id" :value="collection.id">{{ collection.name }}</option>
        </select>
      </div>
      <div class="button-group">
        <button type="reset" @click="cancel">Cancel</button>
        <button type="submit">{{ submitButtonLabel }}</button>
      </div>
    </form>
  </div>
</template>

<script lang="ts">
import { UrlService } from '../services/UrlService';
import { UrlCollectionService } from '../services/UrlCollectionService';
import type { Url } from '../types/Url';
import type { UrlCollection } from '../types/UrlCollection';
import Swal from 'sweetalert2';

const urlService = new UrlService();
const urlCollectionService = new UrlCollectionService();

export default {
  data() {
    return {
      url: {} as Url,
      collections: [] as UrlCollection[],
    };
  },
  props: ['id'],
  created() {
    // Fetch the URL data if the form is in edit mode
    if (this.$route.params.id) {
      const id = Number(this.$route.params.id);
      this.getUrl(id)
    }

    // Fetch the collections
    this.getCollections();
  },
  computed: {
    formTitle() {
      return this.$route.params.id ? 'Edit URL' : 'Add URL';
    },
    submitButtonLabel() {
      return this.$route.params.id ? 'Update' : 'Submit';
    }
  },
  methods: {
    async getUrl(id: number){
      await urlService.getUrl(id).then((res) => {
        if(res.data){
          this.url = res.data as unknown as Url;
        } 
        else this.$router.push('/urls');
      }).catch(() => {
        this.$router.push('/urls');
      }) 
    },
    async submit(){
      if (this.url.collectionId?.toString() == "") this.url.collectionId = null;
      if (this.$route.params.id) {
        await urlService.updateUrl(this.url.id, this.url).then((res) => {
          if(res?.data){
            Swal.fire({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 1500,
              icon: 'success',
              title: 'Success!',
            })
            this.$router.push('/urls');
          }
        }).catch((e) => {
          console.log(e);
        }) 
      } 
      else {
        await urlService.createUrl(this.url).then((res) => {
          if (res?.data){
            Swal.fire({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 1500,
              icon: 'success',
              title: 'Success!',
            })
            this.$router.push('/urls');
          }
        }).catch((e) => {
          console.log(e);
        }) 
      }
    },
    async getCollections(){
      await urlCollectionService.getUrlCollections().then((res) => {
        const data = res.data as unknown as UrlCollection[];
        if (data) this.collections = data; 
      }).catch((e) => {
        console.log(e);
      })
    },
    refreshShortUrl() {
      // Logic to refresh the short URL
      // You can generate a new short URL here and update this.formData.shortUrl
    },
    cancel() {
      this.$router.go(-1);
    },
  }
};
</script>

<style scoped>
.url-form {
  max-width: 500px;
  margin: 0 auto;
  padding: 20px;
  border-radius: 10px;
  background-color: #f5f5f5;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
  font-size: 24px;
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
  color: #555;
}

input[type="text"],
select {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
}

.short-url-input {
  display: flex;
  align-items: center;
}

.short-url-input input[type="text"] {
  flex: 1;
  margin-right: 10px;
}

.short-url-input button {
  padding: 10px;
  background-color: #4caf50;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s ease;
}

.short-url-input button:hover {
  background-color: #45a049;
}

button[type="submit"] {
  display: block;
  width: 100%;
  padding: 14px;
  background-color: #4caf50;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 18px;
  transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
  background-color: #45a049;
}
button[type="reset"] {
  display: block;
  width: 100%;
  padding: 14px;
  background-color: #932f2f;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 18px;
  transition: background-color 0.3s ease;
}

button[type="reset"]:hover {
  background-color: #712222;
}

.button-group {
  display: flex;
  gap: 0.5rem;
  justify-content: flex-end;
}
</style>