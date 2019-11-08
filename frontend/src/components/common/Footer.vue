<template>
  <TheFooter>
    <div class="footer">
      <span class="footer-title ml-1">&copy 2019 CoffeeSign {{ $t('layout.footer') }}</span>
    </div>
  </TheFooter>
</template>

<script>
import { Footer as TheFooter } from '@coreui/vue'

export default {
  name: "Footer",
  components: {
    TheFooter,
  }
}
</script>

<style>

</style>
