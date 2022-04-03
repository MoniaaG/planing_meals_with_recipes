<template>
  <div id="aapp" class="drop-zone">
    <div v-for="item in getList(1)" :key="item.id" class="drag-el" draggable="true">
      {{ item.title }}
    </div>

    <div v-for="item in getList(2)" :key="item.id" class="drag-el" draggable="true">
      {{ item.title }}
    </div>
  </div>
</template>

<script>
//import { ref } from 'vue'
import Vue from 'vue'

export default {
  setup () {
    const items = ref([
      { id: 0, title: 'Item A', list: 1},
      { id: 1, title: 'Item B', list: 1},
      { id: 2, title: 'Item C', list: 2},
    ])

    methods: {
        const getList = (list) => {
            return items.value.filter((item) => item.list == list)
        }
    }

    return {
      getList,
    }
  },
}
</script>

<style>
#aapp {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}

.drag-zone {
  width: 50%;
  margin: 50px auto;
  background-color: red;
  padding: 10px;
  min-height: 10px;
}

.drag-el {
  background-color: blue;
  color: white;
  padding: 5px;
  margin-bottom: 10px;
}

.drag-el:nth-last-of-type(1) {
  margin-bottom: 0;
}
</style>