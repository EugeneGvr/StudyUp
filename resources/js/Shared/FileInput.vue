<template>
  <div>
      <div class="uploaded-image">
        <img :src="showFile(value)" class="md\:max-w-xs md\:max-h-xs"/>
      </div>
    <label v-if="label" class="form-label">{{ label }}:</label>
      <div class="form-input p-2" :class="{ error: errors.length }">
          <input ref="file" type="file" :accept="accept" class="hidden" @change="change">
          <div class="flex items-center justify-between">
              <button v-if="!value" type="button" class="px-4 py-1 bg-grey-dark hover:bg-grey-darker rounded-sm text-xs font-medium text-white" @click="browse">
                  Browse
              </button>
              <div v-if="value" class="flex-1 pr-1">
                  {{ value.name }}
                  <span class="text-grey-dark text-xs">
                      ({{ filesize(value.size) }})
                  </span>
              </div>
              <button  v-if="value" type="button" class="px-4 py-1 bg-grey-dark hover:bg-grey-darker rounded-sm text-xs font-medium text-white" @click="remove">
                  Remove
              </button>
          </div>
      </div>
    <div v-if="errors.length" class="form-error">{{ errors[0] }}</div>
  </div>
</template>

<script>
export default {
  props: {
    path: String,
    value: File,
    label: String,
    accept: String,
    errors: {
      type: Array,
      default: () => [],
    },
  },
    data() {
      return {
          deleteUploadedFile: false,
      }
    },
  watch: {
    value(value) {
      if (!value) {
        this.$refs.file.value = ''
      }
    },
  },
  methods: {
      showFile(uploadedFile) {
        if (uploadedFile === true) {
            return '/images/default/avatar/male_avatar.svg';
        }
          return uploadedFile ? URL.createObjectURL(uploadedFile) : this.path;
      },
    filesize(size) {
      var i = Math.floor(Math.log(size) / Math.log(1024))
      return (size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i]
    },
    browse() {
      this.$refs.file.click()
    },
    change(e) {
      this.$emit('input', e.target.files[0])
    },
    remove() {
        this.$emit('input', null)
    },
  },
}
</script>
