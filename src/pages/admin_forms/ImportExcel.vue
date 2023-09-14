<template>
  <q-layout view="hHh Lpr lFf">
    <q-page-container class="bg-grey-2">
      <div @keyup="nextItem">
        <q-page
          padding
          class="row items-center justify-center"
          style="background: linear-gradient(#74c588, #0ad13c)"
        >
          <div class="row full-width">
            <div class="col-md-8 offset-md-2 col-xs-12 q-pa-xs">
              <q-card flat class="bg-white text-black">
                <q-card-section class="bg-blue-14">
                  <h4 class="text-h5 text-white q-my-xs text-center">
                    {{ title }}
                  </h4>
                </q-card-section>
                <div class="row full-width">
                  <div class="col-md-12 col-xs-12 q-pa-xs">
                    <q-table class="table" border="0">
                      <tr class="row">
                        <td id="empty_first_cell"></td>
                        <td
                          class="column"
                          v-for="(_, j) in cols"
                          :key="'header' + j"
                        >
                          {{ letters[j] }}
                        </td>
                      </tr>
                      <tr class="row" v-for="(_, i) in rows" :key="i">
                        <td class="column">
                          {{ i + 1 }}
                        </td>
                        <td
                          class="column"
                          v-for="(__, j) in cols"
                          :key="i + '-' + j"
                          :class="{
                            column_selected: active(i, j),
                            column_inactive: !active(i, j),
                          }"
                          @click="activate(i, j)"
                        >
                          <div v-if="active(i, j)">
                            <input
                              :ref="'input' + i + '-' + j"
                              v-model="raw_values[i][j]"
                              @keydown.enter.prevent="ui_enter()"
                              @keydown.esc="ui_esc()"
                            />
                          </div>
                          <div
                            v-else
                            v-html="
                              computed_value_formatter(
                                computed_values[i][j].value
                              )
                            "
                          />
                        </td>
                      </tr>
                    </q-table>
                  </div>
                </div>
              </q-card>
            </div>
          </div>
        </q-page>
      </div>
    </q-page-container>
  </q-layout>
</template>

<script>
import {
  ref,
  reactive,
  computed,
  watchEffect,
  toRefs,
  nextTick,
  onUpdated,
} from "vue";

export default {
  name: "App",
  components: {},
  data() {
    return {
      title: "รายงาน",
      ui_editing_i: null,
      ui_editing_j: null,
    };
  },
  methods: {
    get_dom_input(i, j) {
      return this.$refs["input" + i + "-" + j];
    },
    activate(i, j) {
      this.ui_editing_i = i;
      this.ui_editing_j = j;
      nextTick(() => this.get_dom_input(i, j).focus());
    },
    active(i, j) {
      return this.ui_editing_i === i && this.ui_editing_j === j;
    },
    unselect() {
      this.ui_editing_i = null;
      this.ui_editing_j = null;
    },
    computed_value_formatter(str) {
      if (str === undefined || str === null) return "none";
      return str;
    },
    ui_enter() {
      if (this.ui_editing_i < this.rows - 1)
        this.activate(this.ui_editing_i + 1, this.ui_editing_j);
      else this.unselect();
    },
    ui_esc() {
      this.unselect();
    },
  },
  setup() {
    const rows = ref(30),
      cols = ref(26);

    /* if a string codes a number, return the number, else return a string */
    const as_number = (raw_cell) =>
      /^[0-9]+(\.[0-9]+)?$/.test(raw_cell)
        ? Number.parseFloat(raw_cell)
        : raw_cell;

    const make_table = (val = "", _rows = rows.value, _cols = cols.value) =>
      Array(_rows)
        .fill(null)
        .map(() => Array(_cols).fill(val));

    const raw_values = reactive(make_table("", rows.value, cols.value));
    const computed_values = reactive(
      make_table(undefined, rows.value, cols.value)
    );

    /* a useful metric for debugging: how many times did cell (re)computations occur? */
    const calculations = ref(0);

    const letters = Array(26)
      .fill(0)
      .map((_, i) => String.fromCharCode("A".charCodeAt(0) + i));

    const transpile = (str) => {
      let cell_replacer = (match, prepend, col, row) => {
        col = letters.indexOf(col);
        row = Number.parseInt(row) - 1;
        return prepend + ` computed_values[${row}][${col}].value `;
      };
      return str.replace(/(^|[^A-Z])([A-Z])([0-9]+)/g, cell_replacer);
    };

    const computed_cell_generator = (i, j) => {
      const computed_cell = computed(() => {
        // we don't want Vue to think that the value of a computed_cell depends on the value of `calculations`
        nextTick(() => ++calculations.value);

        let raw_cell = raw_values[i][j].trim();
        if (!raw_cell || raw_cell[0] != "=") return as_number(raw_cell);

        let user_code = raw_cell.substring(1);
        let code = transpile(user_code);
        try {
          // the constructor of a Function receives the body of a function as a string
          let fn = new Function(["computed_values"], `return ${code};`);
          return fn(computed_values);
        } catch (e) {
          return "ERROR";
        }
      });
      return computed_cell;
    };

    for (let i = 0; i < rows.value; ++i)
      for (let j = 0; j < cols.value; ++j)
        computed_values[i][j] = computed_cell_generator(i, j);

    return { raw_values, computed_values, rows, cols, letters, calculations };
  },
};
</script>

<style scoped>
.table {
  margin-left: auto;
  margin-right: auto;
  margin-top: 1ex;
  border-collapse: collapse;
}

.column {
  box-sizing: border-box;
  border: 1px lightgray solid;
}

.column:first-child {
  background: #f6f6f6;
  min-width: 3em;
}

.column:not(:first-child) {
  min-width: 4em;
}

.row:first-child {
  background: #f6f6f6;
}

#empty_first_cell {
  background: white;
}

.column_selected {
  border: 2px cornflowerblue solid !important;
  padding: 0px;
}

.column_selected input,
.column_selected input:active,
.column_selected input:focus {
  outline: none;
  border: none;
}
</style>
