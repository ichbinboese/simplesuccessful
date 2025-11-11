<template>
  <div>
    <h2>Videos</h2>
    <ul>
      <li v-for="v in videos" :key="v.id">
        {{ v.title }} — <button @click="$emit('createLink', v)">Link erzeugen</button>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: ['videos']
}
</script>
