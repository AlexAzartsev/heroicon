<template>
  <PanelItem :field="field" :index="index" >
    <template #value>
      <span class="heroicon-index" v-html="field.value"> </span>
    </template>
  </PanelItem>
</template>

<script>
export default {
  props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
};
</script>
<style>
.heroicon-index svg {
  width: 60px;
  height: 60px;
}
</style>
