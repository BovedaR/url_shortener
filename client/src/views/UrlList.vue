<template>
  <div class="url-list">
    <div class="add-button-container">
      <button class="add-collection-button" @click="urlCollectionForm()">Add Collection</button>
      <button class="add-url-button" @click="urlForm()">Add URL</button>
    </div>
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Original URL</th>
          <th>Short URL</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="item in tableItems" :key="item.id">
          <tr v-if="item.type === 'url'">
            <td>{{ item.name }}</td>
            <td>{{ item.originalUrl }}</td>
            <td><a :href="item.shortUrl">{{ item.shortUrl }}</a></td>
            <td class="actions">
              <button class="edit-button" @click="urlForm(item.id)">Edit</button>
              <button class="remove-button" @click="deleteUrl(item.id)">Remove</button>
            </td>
          </tr>
          <tr v-else-if="item.type === 'collection'">
            <td colspan="4">
              <div class="collection">
                <div class="collection-header">
                  <span class="collection-name" @click="toggleCollection(item.id)">
                    {{ item.name + " (" + item.urls?.length + ") " + (item.expanded ? "▲" : "▼")}}
                  </span>
                  <div class="actions">
                    <button class="edit-button" @click="urlCollectionForm(item.id)">Edit</button>
                    <button class="remove-button" @click="deleteUrlCollection(item.id)">Remove</button>
                  </div>
                </div>
                <div v-if="item.expanded" class="collection-urls">
                  <table>
                    <tbody>
                      <tr v-for="url in item.urls" :key="url.id">
                        <td>{{ url.name }}</td>
                        <td>{{ url.originalUrl }}</td>
                        <td><a :href="url.shortUrl">{{ url.shortUrl }}</a></td>
                        <td class="actions">
                          <button class="edit-button" @click="urlForm(url.id)">Edit</button>
                          <button class="remove-button" @click="deleteUrl(url.id)">Remove</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>

<script lang="ts">
import { UrlService } from '../services/UrlService';
import { UrlCollectionService } from '../services/UrlCollectionService';
import Swal from 'sweetalert2';
import type { Url } from '../types/Url';
import type { TableItem } from '../types/TableItems';

const urlService = new UrlService();
const urlCollectionService = new UrlCollectionService();

export default {
  name: "UrlList",
  data() {
    return {
      tableItems: [] as TableItem[],
      items: [] as Url[],
    };
  },
  created() {
    this.urlList();
  },
  methods: {
    urlForm(id?: number) {
      this.$router.push(`/urls/form/${id ? id.toString(): ""}`);
    },
    urlCollectionForm(id?: number) {
      this.$router.push(`/urlcollections/form/${id ? id.toString(): ""}`);
    },
    toggleCollection(id: number) {
      const collection = this.tableItems.find(item => item.id === id && item.type === "collection");
      if (collection) {
        collection.expanded = !collection.expanded;
      }
    },
    async urlList(){
      urlService.getUrls().then((res) => {
        const data = res.data as unknown as Url[];
        if (data) {
          this.loadTable(data); 
        }
      }).catch((e) => {
        console.log(e);
      })
    },
    loadTable(data: Url[]){
      data.forEach((item) => {
        if (item.collection) {
          const collection = this.tableItems.find((collection) => collection.id === item.collection?.id && collection.type === "collection");
          if (collection) {
            collection.urls?.push(item);
          } else {
            this.tableItems.push(
              {
                id: item.collection.id,
                name: item.collection?.name,
                urls: [item],
                type: "collection",
                expanded: false,
              } as TableItem
            );
          }
        } else {
          this.tableItems.push({
            id: item.id,
            name: item.name,
            originalUrl: item.originalUrl,
            shortUrl: item.shortUrl,
            type: "url",
          });
        }
      });
    },
    async deleteUrl(id: number){
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#243540',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          urlService.deleteUrl(id).then((res) => {
            if (res.data){
              this.tableItems = [];
              this.urlList();
            } 
          }).catch((e) => {
              console.log(e);
          })
        }
      })
    },
    async deleteUrlCollection(id: number){
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#243540',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          urlCollectionService.deleteUrlCollection(id).then((res) => {
            if (res.data.data){
              this.tableItems = [];
              this.urlList();
            } 
          }).catch((e) => {
              console.log(e);
          })
        }
      })
    },
  },
};
</script>

<style scoped>
.url-list {
  margin: 20px;
}

.add-button-container {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 10px;
}

.add-collection-button {
  padding: 8px 16px;
  background-color: #4caf50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s ease;
}
.add-url-button {
  padding: 8px 16px;
  background-color: #4caf50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
  margin-left: 10px;
  transition: background-color 0.3s ease;
}

.add-collection-button:hover {
  background-color: #45a049;
}
.add-url-button:hover {
  background-color: #45a049;
}

.table-container {
  overflow-x: auto;
}

.url-table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}

th, td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
  word-wrap: break-word;
}

th {
  background-color: #f2f2f2;
  font-weight: bold;
}

.edit-button, .remove-button {
  padding: 8px 12px;
  background-color: #2196f3;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s ease;
}

.edit-button:hover, .remove-button:hover {
  background-color: #0b7dda;
}

.collection-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.actions {
  display: flex;
  gap: 0.5rem;
  justify-content: flex-end;
}

table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}
</style>