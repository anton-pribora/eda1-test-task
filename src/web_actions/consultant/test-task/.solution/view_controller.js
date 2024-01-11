app.component('solution-view-form', {
  template: '#solutionViewForm',
  data: function () {
    const ingredientTypes = {};

    for (const type of this.types) {
      ingredientTypes[type.code] = {
        error: null,
        number: 0
      };
    }

    return {
      loading: false,
      result: null,
      ingredientTypes,
      ingredients: this.initIngredients,
      view: 'table',
      calcType: 'mysql'
    };
  },
  props: {
    types: Array,
    initIngredients: String
  },
  watch: {
    ingredientTypes: {
      handler(n,o) {
        for (const [key, value] of Object.entries(n)) {
          if (isNaN(value.number)) {
            value.error = 'Значение не является числом';
          } else if (value.number < 0) {
            value.error = 'Значение не должно быть меньше ноля';
          } else if (value.number > 5) {
            value.error = 'Значение не должно быть больше 5';
          } else if (value.error) {
            value.error = undefined;
          }
        }
      },
      deep: true
    },
    ingredients(newValue) {
      this.parseIngredients(newValue);
    }
  },
  methods: {
    async submit() {
      this.loading = true;
      this.result = await this.$delay(this.$post('', {action: 'calculate', ingredients: this.ingredients, calcType: this.calcType})) || {error: app.config.globalProperties.$ajaxLoaderLastError};
      this.loading = false;
    },
    onChange() {
      this._dirty = true;
    },
    parseIngredients(newValue) {
      for (const type of this.types) {
        this.ingredientTypes[type.code].number = [...newValue.matchAll(type.code)].length;
      }
    },
    addIngredient(code) {
      this.ingredients += code;
    },
    removeIngredient(code) {
      const index = this.ingredients.lastIndexOf(code);

      if (index > -1) {
        this.ingredients = this.ingredients.slice(0, index) + this.ingredients.slice(index + 1);
      }
    }
  },
  mounted() {
    this.parseIngredients(this.initIngredients);
  }
});
