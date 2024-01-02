<template>
  <div class="url-collection-form">
    <h2>{{ formTitle }}</h2>
    <form @submit.prevent="submit">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" v-model="collection.name" required>
      </div>
      <div class="button-group">
        <button type="reset" @click="cancel">Cancel</button>
        <button type="submit">{{ submitButtonLabel }}</button>
      </div>
    </form>
  </div>
</template>

<script lang="ts">
import { UrlCollectionService } from '../services/UrlCollectionService';
import type { UrlCollection } from '../types/UrlCollection';
import Swal from 'sweetalert2';

const urlCollectionService = new UrlCollectionService();

export default {
  data() {
    return {
      collection: {} as UrlCollection,
    };
  },
  props: ['id'],
  created() {
    if (this.$route.params.id) {
      const id = Number(this.$route.params.id);
      this.getUrlCollection(id)
    }
  },
  computed: {
    formTitle() {
      return this.$route.params.id ? 'Edit Collection' : 'Add Collection';
    },
    submitButtonLabel() {
      return this.$route.params.id ? 'Update' : 'Submit';
    }
  },
  methods: {
    async getUrlCollection(id: number){
      await urlCollectionService.getUrlCollection(id).then((res) => {
        if (res.data){
          this.collection = res.data as unknown as UrlCollection;
        } 
        else this.$router.push('/urls');
      }).catch(() => {
        this.$router.push('/urls');
      }) 
    },
    async submit() {
      if (this.$route.params.id) {
        await urlCollectionService.updateUrlCollection(this.collection.id, this.collection).then((res) => {
          if(res.data){
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
        await urlCollectionService.createUrlCollection(this.collection).then((res) => {
          if (res.data){
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
    cancel() {
      this.$router.go(-1);
    },
  }
};
</script>

<style scoped>
.url-collection-form {
  max-width: 500px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 10px;
  background-color: #f5f5f5;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
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

input[type="text"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

button[type="submit"] {
  display: block;
  width: 100%;
  padding: 12px;
  background-color: #4caf50;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
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