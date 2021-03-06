<template >
  <datatable
    :type="type"
    :group="group"
    :label="label"
    :fields="getFields"
    
    :methods="{
      create: create,
      edit: edit,
      store: store,
      update: update
    }"

    ref="datatable"
  > 
    <template v-slot:logo-body="slotProps">
      <img class="tilt" :src="slotProps.row.logo ? slotProps.row.logo.thumb : '/images/placeholder.png'" />
    </template>

    <template v-slot:categories-body="slotProps">
      <transition-group name="list">
        <el-popover
          v-for="item in slotProps.row.categories.filter( (i, index) => index < 3)"
          :key="item.id"
          placement="top-end"
          width="300"
          trigger="hover"
          :disabled="typeof item.title === 'string' ? item.title.length <= 20 : false"
          :content="item.title"
        >
          <span
            slot="reference"
            class="badge badge-default ml-1 hvr-grow-shadow hvr-icon-grow">
            <i class="tim-icons icon-bullet-list-67 hvr-icon"></i>
            {{ item.title | truncate(20) }}
          </span>
        </el-popover>

        <el-dropdown v-if="slotProps.row.categories.length > 3" :key="slotProps.row.categories.map((c) => c.id).join(',')">
          <span class="el-dropdown-link badge badge-default">
            باقی گروه ها <i class="el-icon-arrow-down el-icon--right"></i>
          </span>
          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item
              v-for="item in slotProps.row.categories.filter( (category, index) => index < 3)"
              :key="item.id">
              {{ item.title }}
            </el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </transition-group>
    </template>

    <template v-slot:code-body="slotProps">
      <span class="badge badge-primary color-badge p-2" :style="{ background: slotProps.row.code }">
        {{ slotProps.row.code }}
      </span>
    </template>

    <template slot="modal">
      <slot></slot>

      <br />
      <div class="row">
        <div :class="has_logo ? 'col-7' : 'col-12'">
          <base-input :label="'دسته بندی های ' + label">
            <el-tree
              class="rtl"
              dir="ltr"
              :data="$store.state.group.category"
              :props="defaultProps"
              :accordion="true"
              ref="categories"
              show-checkbox
              node-key="id"
              @check-change="changeSelectedCategories"
              :default-checked-keys="selected('categories')"
              :default-expanded-keys="selected('categories')"
              empty-text="هیچ دسته بندی ای یافت نشد :("
            >
            </el-tree>
          </base-input>
          <small slot="helperText" id="emailHelp" class="form-text text-muted">برای انتخاب هر دسته بندی تیک قبل آن را انتخاب کنید</small>
        </div>
        <div class="col-5" v-if="has_logo">
          <base-input :label="'لوگوی ' + label">
            <el-upload
              class="avatar-uploader"
              action="/"
              :auto-upload="false"
              :show-file-list="false"
              :on-change="addImage">
              <div v-if="$store.state[group].form[type].logo.url">
                <img :src="$store.state[group].form[type].logo.url" class="avatar" />
                <i @click.prevent="deleteImage" class="el-icon-delete avatar-uploader-icon"></i>
              </div>
              <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
            <small slot="helperText" id="emailHelp" class="form-text text-muted">لوگوی مورد نظر خود را انتخاب کنید</small>
          </base-input>
        </div>
      </div>
    </template>
  </datatable>
</template>

<script>
import Datatable from '../../components/BaseDatatable.vue'

import createMixin from '../../mixins/createMixin'
import initDatatable from '../../mixins/initDatatable'

export default {
  props: [
    'fields',
    'type',
    'label',
    'has_logo',
    'validate',
    'plural'
  ],
  components: {
    Datatable
  },
  mixins: [
    initDatatable,
    createMixin,
  ],
  data() {
    return {
        group: 'feature',

        defaultProps: {
          children: 'childs',
          label: 'title',
        },
    }
  },
  mounted() {
    this.$store.dispatch('getData', {
      group: 'group',
      type: 'category',
    })
  },
  methods: {
    closePanel() {
      this.$refs.datatable.closePanel();
    },
    afterCreate()
    {
      setTimeout(() => this.$refs.categories.setCheckedKeys([]) , 100);
    },
    afterEdit(row)
    {
      setTimeout(() => this.$refs.categories.setCheckedKeys(
        row.categories.map(i => i.id)
      ), 100);
    },
    changeSelectedCategories() {
      this.$store.commit('setFormData', {
        group: this.group,
        type: this.type,
        field: 'categories',
        value: this.$refs.categories.getCheckedKeys()
      })
    },
  },
  computed: {
    getFields() {
      if ( this.has_logo ) {
        this.fields.unshift({
          field: 'logo',
          label: 'لوگو',
          icon: 'icon-image-02'
        })
      }

      this.fields.push({
        field: 'categories',
        label: 'دسته بندی ها',
        icon: 'icon-bullet-list-67'
      })
      
      return this.fields;
    },
    
    allQuery() {
      let query = ''

      if ( this.has_logo )
        query += 'logo { id file_name thumb medium }'

      query += `
        ${this.queryFields}
        categories {
          id
          title
        }`

      return query
    },

    queryFields() {
      return this.fields.filter( i => i.field !== 'logo' && i.field !== 'categories' ).map( i => i.field ).join(' ');
    },
  },
}
</script>

<style>
.color-badge {
  box-shadow: 0px 3px 20px -4px #888;
  text-shadow: 1px 1px 10px #777;
}
</style>
