<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText">
    <template slot="field">
      <div class="flex flex-row">
        <div v-if="value" class="icon-preview mb-4">
          <span class="relative inline-block p-8 border border-gray-300 rounded-md">
            <span v-html="value"> </span>
            <span class="close-icon absolute top-0 right-0 cursor-pointer invisible" @click="clear">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </span>
          </span>
        </div>
        <div class="flex justify-center items-center">
          <button class="btn btn-default btn-primary mb-3 ml-3" @click.prevent="toggleModal">
            {{ openModalText }}
          </button>
          <button
            v-if="field.editor"
            class="btn btn-default btn-primary mb-3 ml-3"
            @click.prevent="toggleEditor"
          >
            {{ editButtonText }}
          </button>
        </div>
      </div>
      <transition name="fade">
        <textarea
          v-show="editorOpened"
          :id="field.name"
          type="text"
          class="w-full form-control form-input form-input-bordered h-36"
          :class="errorClasses"
          :placeholder="field.name"
          v-model="value"
        />
      </transition>

      <modal v-if="modalOpened" @close="closeModal" class="heroicon-modal">
        <div class="bg-white rounded-lg shadow-lg">
          <div class="px-8 py-6 border-b relative" style="border-color: #e0e0e0">
            <heading :level="2" class="mb-0 px-10">{{ __('Select Icon') }}</heading>
            <a href="#" class="heroicon-close" @click.prevent="closeModal">
              <svg
                class="w-10 h-10"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </a>
          </div>
          <div class="px-8 py-4 border-b">
            <div class="flex flex-wrap -mx-4">
              <div class="w-1/3 px-4">
                <select
                  id="type"
                  class="w-full form-control form-select"
                  v-model="filter.type"
                  :disabled="disableOptions"
                >
                  <option v-for="opt in iconOptions" :value="opt.value" :key="opt.value">
                    {{ opt.label }}
                  </option>
                </select>
              </div>
              <div class="w-2/3 px-4">
                <input
                  type="text"
                  id="search"
                  class="w-full form-control form-input form-input-bordered"
                  placeholder="Search icons"
                  v-model="filter.search"
                  @keypress.enter.prevent
                />
              </div>
            </div>
          </div>
          <div class="px-8 py-6 heroicon-inner">
            <div class="flex flex-wrap items-stretch -mx-2">
              <div
                v-for="icon in filteredIcons"
                :key="`${icon.type}_${icon.name}`"
                class="
                  flex flex-col
                  items-center
                  justify-center
                  text-center
                  px-2
                  w-1/6
                  cursor-pointer
                  mb-4
                  min-h-90px
                "
                style="outline: 1px solid #e0e0e0; outline-offset: -0.5rem"
                @click="saveIcon(icon)"
              >
                <div v-html="icon.content" class="w-12 h-12 icon-container"></div>
                <div>{{ icon.name }}</div>
              </div>
            </div>
          </div>
        </div>
      </modal>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import { icons } from '../icons';

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],
  data() {
    return {
      defaultIcons: icons,
      modalOpened: false,
      editorOpened: false,
      value: '',
      filter: {
        type: '',
        search: '',
      },
    };
  },
  methods: {
    setInitialValue() {
      this.value = this.field.value || '';
    },
    fill(formData) {
      formData.append(this.field.attribute, this.value || '');
    },
    clear() {
      this.value = '';
    },
    toggleModal() {
      this.modalOpened = !this.modalOpened;
    },
    toggleEditor() {
      this.editorOpened = !this.editorOpened;
    },
    closeModal() {
      this.modalOpened = false;
    },
    saveIcon(icon) {
      this.value = icon.content;
      this.filter.type = '';
      this.filter.search = '';
      this.closeModal();
    },
  },
  computed: {
    icons() {
      let allIcons = this.defaultIcons;
      const enabledTypes = [];
      this.field.icons.forEach((iconSet) => {
        enabledTypes.push(iconSet.value);
        if (typeof iconSet.icons !== 'undefined') {
          allIcons = [...allIcons, ...iconSet.icons];
        }
      });
      return allIcons.filter((icon) => enabledTypes.includes(icon.type));
    },
    filteredIcons() {
      let filteredIcons = this.icons;
      if (this.filter.type) {
        filteredIcons = filteredIcons.filter((icon) => icon.type === this.filter.type);
      }

      if (this.filter.search) {
        filteredIcons = filteredIcons.filter((icon) => icon.name.includes(this.filter.search));
      }
      return filteredIcons;
    },
    editButtonText() {
      if (this.editorOpened) {
        return 'Close';
      }
      return 'Edit';
    },
    openModalText() {
      if (this.value) {
        return 'Change icon';
      }
      return 'Add icon';
    },
    iconOptions() {
      if (this.field.icons.length > 1) {
        return [{ value: '', label: 'All' }, ...this.field.icons];
      }
      return this.field.icons;
    },
    disableOptions() {
      return this.field.icons.length === 1;
    },
  },
  created() {
    const escapeHandler = (e) => {
      if (e.key === 'Escape' && this.modalOpened) {
        this.closeModal();
      }
    };
    document.addEventListener('keydown', escapeHandler);
    this.$once('hook:destroyed', () => {
      document.removeEventListener('keydown', escapeHandler);
    });
    this.filter.type = this.iconOptions[0].value;
  },
};
</script>
<style scoped>
.icon-preview svg {
  width: 60px;
  height: 60px;
}

.icon-container > svg {
  max-height: 100%;
  max-width: 100%;
}

.icon-preview:hover .close-icon {
  visibility: visible;
}

.close-icon {
  transform: translate(50%, -50%);
  opacity: 0.75;
  transition: visibility 0.4s linear;
}

.close-icon:hover {
  opacity: 1;
}

.close-icon svg {
  width: 30px;
  height: 30px;
}

.heroicon-modal > div:first-child {
  flex-basis: 0;
  height: 100%;
  flex-direction: column;
}

.heroicon-modal > div:first-child > div {
  position: relative;
  max-height: 80%;
  overflow: hidden;
  width: 60%;
  margin: 0 auto;
  display: flex;
  flex-grow: 1;
}

.heroicon-modal > div:first-child > div > div {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.heroicon-inner {
  height: 80%;
  overflow-y: scroll;
  overflow-x: hidden;
}

.heroicon-close {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  right: 1.5rem;
  font-size: 1.5rem;
  color: #3c4b5f;
}
</style>
