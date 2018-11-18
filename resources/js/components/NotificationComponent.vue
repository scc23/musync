<template>
  <div id="broadcast-notification">    
    <notifications group="general-notification"
                   position="top right"
                   :speed="500"/>
  </div>
</template>

<script>
export default {
    props: {
            "notificationText": String
    },

    watch: {
        notificationText: function () {
            this.show('general-notification', 'success');
        }
    },

    data () {
        return {
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
      const text = this.notificationText;
      this.$notify({
        group,
        title: `MuSync`,
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
