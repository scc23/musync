<template>
  <div id="broadcast-notification">    
    <notifications group="broadcast-notification"
                   position="top right"
                   :speed="500"/>
  </div>
</template>

<script>
export default {
    props: {
            "broadcastNotificationText": String
    },

    watch: {
        broadcastNotificationText: function () {
            this.show('broadcast-notification', 'success');
        }
    },

    data () {
        return {
            notificationText: "", 
            animation: {
                enter: {
                    opacity: [1, 0],
                    translateX: [0, -300],
                    scale: [1, 0.2]
                },
                leave: {
                    opacity: 0,
                    height: 0
                }
            }
        }
  },

  methods: {
    show (group, type = '') {
      const text = this.broadcastNotificationText;
      this.$notify({
        group,
        title: `Musync`,
        text,
        type,
      })
    },
    clean (group) {
      this.$notify({ group, clean: true })
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
