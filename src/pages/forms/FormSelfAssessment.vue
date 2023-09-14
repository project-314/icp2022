<template>
  <q-layout view="hHh Lpr lFf">
    <q-page-container class="bg-grey-2">
      <q-page
        padding
        class="items-center justify-center"
        style="background: linear-gradient(#74c588, #0ad13c)"
      >
        <div class="full-width">
          <div class="col-md-8 offset-md-2 col-xs-12 q-pa-xs">
            <q-card flat class="bg-white text-black">
              <q-card-section class="bg-blue-14">
                <h4 class="text-h5 text-white q-my-xs text-center">
                  {{ title }}
                </h4>
              </q-card-section>
              <div class="row">
                <div class="col-md-12 col-xs-12 q-pa-xs">
                  <q-form
                    @submit.prevent="submitForm"
                    @reset="resetForm"
                    method="post"
                    class="q-gutter-md"
                  >
                    <!-- อาชีพเป้าหมาย + คุณสมบัติ-->
                    <div class="row">
                      <!-- แผนอาชีพ -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-select
                          @filter="filterPlan_career"
                          use-input
                          color="green"
                          v-model="plan_career_id"
                          :options="plan_career.options"
                          label="อาชีพเป้าหมาย *"
                          emit-value
                          map-options
                          @update:model-value="
                            (plan_career_id) => getQualification(plan_career_id)
                          "
                        >
                          <template v-slot:prepend>
                            <q-icon name="work_history" />
                          </template>
                          <template v-slot:option="scope">
                            <q-item v-bind="scope.itemProps">
                              <q-item-section avatar>
                                <q-icon :name="scope.opt.icon" />
                              </q-item-section>
                              <q-item-section>
                                <q-item-label>{{
                                  scope.opt.label
                                }}</q-item-label>
                                <q-item-label caption>{{
                                  scope.opt.description
                                }}</q-item-label>
                              </q-item-section>
                            </q-item>
                          </template>
                          <template v-if="plan_career_id" v-slot:append>
                            <q-icon
                              name="cancel"
                              @click.stop.prevent="plan_career_id = null"
                              class="cursor-pointer"
                            />
                          </template>
                        </q-select>
                      </div>
                      <!-- คุณสมบัติ -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-select
                          @filter="filterQa_plan_career"
                          use-input
                          color="green"
                          v-model="qa_plan_career_id"
                          :options="qa_plan_career.options"
                          label="คุณสมบัติที่ต้องการ *"
                          emit-value
                          map-options
                        >
                          <template v-slot:prepend>
                            <q-icon name="fact_check" />
                          </template>
                          <template v-slot:option="scope">
                            <q-item v-bind="scope.itemProps">
                              <q-item-section avatar>
                                <q-icon :name="scope.opt.icon" />
                              </q-item-section>
                              <q-item-section>
                                <q-item-label>{{
                                  scope.opt.label
                                }}</q-item-label>
                                <q-item-label caption>{{
                                  scope.opt.description
                                }}</q-item-label>
                              </q-item-section>
                            </q-item>
                          </template>
                          <template v-if="qa_plan_career_id" v-slot:append>
                            <q-icon
                              name="cancel"
                              @click.stop.prevent="qa_plan_career_id = null"
                              class="cursor-pointer"
                            />
                          </template>
                        </q-select>
                      </div>
                    </div>
                    <!-- เดือนประเมิน -->
                    <div class="row">
                      <!-- วันประเมินตนเอง -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-input
                          filled
                          v-model="self_assessment_date"
                          label="วันประเมินตนเอง *"
                          mask="##/##/####"
                          fill-mask
                          hint="วัน/เดือน/ปี ค.ศ.(DD/MM/YYYY)"
                          today-btn
                          clearable
                        >
                          <template v-slot:append>
                            <q-icon name="event" class="cursor-pointer">
                              <q-popup-proxy
                                cover
                                transition-show="scale"
                                transition-hide="scale"
                              >
                                <q-date
                                  v-model="self_assessment_date"
                                  mask="DD/MM/YYYY"
                                >
                                  <div class="row items-center justify-end">
                                    <q-btn
                                      v-close-popup
                                      label="Close"
                                      color="primary"
                                      flat
                                    />
                                  </div>
                                </q-date>
                              </q-popup-proxy>
                            </q-icon>
                          </template>
                        </q-input>
                      </div>
                      <!-- ประเมินตนเอง -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-select
                          @filter="filterPerform"
                          use-input
                          color="green"
                          v-model="perform_id"
                          :options="perform.options"
                          label="ผลการพัฒนาตนเอง *"
                          emit-value
                          map-options
                        >
                          <template v-slot:prepend>
                            <q-icon name="flag_circle" />
                          </template>
                          <template v-slot:option="scope">
                            <q-item v-bind="scope.itemProps">
                              <q-item-section avatar>
                                <q-icon :name="scope.opt.icon" />
                              </q-item-section>
                              <q-item-section>
                                <q-item-label>{{
                                  scope.opt.label
                                }}</q-item-label>
                                <q-item-label caption>{{
                                  scope.opt.description
                                }}</q-item-label>
                              </q-item-section>
                            </q-item>
                          </template>
                          <template v-if="perform_id" v-slot:append>
                            <q-icon
                              name="cancel"
                              @click.stop.prevent="perform_id = null"
                              class="cursor-pointer"
                            />
                          </template>
                        </q-select>
                      </div>
                    </div>
                    <!-- การอ้างอิง -->
                    <div class="row">
                      <!-- การอ้างอิง -->
                      <div class="col-md-12 col-xs-12 q-pa-xs">
                        <div class="q-pa-xs">
                          <q-table
                            title="ข้อมูลผลงาน"
                            :rows="references1"
                            :columns="references"
                            row-key="name"
                            :filter="filter_reference"
                            :loading="loading"
                            :visible-columns="visibleColumnsReference"
                          >
                            <template v-slot:top-right="props">
                              <div class="row">
                                <div class="col-md-4 col-xs-4 q-pa-xs">
                                  <q-input
                                    borderless
                                    dense
                                    debounce="300"
                                    v-model="filter_reference"
                                    placeholder="ค้นหาผลงาน"
                                  >
                                    <template v-slot:append>
                                      <q-icon name="search" />
                                    </template>
                                  </q-input>
                                </div>
                                <!-- ส่งออกไฟล์ -->
                                <div class="col-md-4 col-xs-4 q-pa-xs">
                                  <q-input
                                    borderless
                                    dense
                                    debounce="300"
                                    v-model="file_export"
                                    placeholder="ชื่อไฟล์นำออก"
                                    outlined
                                  >
                                    <template v-slot:append>
                                      <q-icon name="save" />
                                    </template>
                                  </q-input>
                                </div>
                                <div class="col-md-4 col-xs-4 q-pa-xs">
                                  <q-btn
                                    flat
                                    icon-right="archive"
                                    label="ส่งออกไฟล์"
                                    @click="exportTable()"
                                  />
                                </div>
                                <div class="col-md-4 col-xs-4 q-pa-xs">
                                  <q-btn
                                    rounded
                                    flat
                                    @click="show_dialog = true"
                                    icon="add_circle"
                                    label="เพิ่มข้อมูล"
                                    no-caps
                                  >
                                    <q-tooltip class="bg-accent"
                                      >เพิ่มแหล่งอ้างอิง/ผลงาน</q-tooltip
                                    >
                                  </q-btn>
                                </div>
                                <div class="col-md-4 col-xs-4 q-pa-xs">
                                  <q-select
                                    v-model="visibleColumnsReference"
                                    multiple
                                    outlined
                                    dense
                                    options-dense
                                    :display-value="$q.lang.table.columns"
                                    emit-value
                                    map-options
                                    :options="references"
                                    option-value="name"
                                    options-cover
                                    style="min-width: 150px"
                                  />
                                </div>
                                <div class="col-md-4 col-xs-4 q-pa-xs">
                                  <q-btn
                                    flat
                                    round
                                    dense
                                    :icon="
                                      props.inFullscreen
                                        ? 'fullscreen_exit'
                                        : 'fullscreen'
                                    "
                                    @click="props.toggleFullscreen"
                                    class="q-ml-md"
                                  />
                                </div>
                              </div>
                              <div class="q-pa-sm q-gutter-sm">
                                <q-dialog v-model="show_dialog">
                                  <q-card>
                                    <q-card-section>
                                      <div class="text-h6">
                                        เพิ่มข้อมลแหล่งอ้างอิง/ผลงาน
                                      </div>
                                    </q-card-section>
                                    <q-card-section>
                                      <q-input
                                        v-model="editedReference.reference_id"
                                        label="รหัสผลงาน"
                                        :disable="true"
                                      />
                                      <q-input
                                        v-model="
                                          editedReference.reference_description
                                        "
                                        label="แหล่งช้อมูลอ้างอิง/ผลงาน"
                                      />
                                    </q-card-section>
                                    <q-card-actions align="right">
                                      <q-btn
                                        flat
                                        :label="btnLabel_"
                                        color="primary"
                                        v-close-popup
                                        @click="addRow"
                                      ></q-btn>
                                    </q-card-actions>
                                  </q-card>
                                </q-dialog>
                              </div>
                            </template>
                            <template v-slot:body-cell-actions="props">
                              <q-td :props="props">
                                <q-btn
                                  icon="mode_edit"
                                  label="แก้ไข"
                                  @click="editItem(props.row)"
                                ></q-btn>
                                <q-btn
                                  icon="delete"
                                  label="ลบ"
                                  @click="deleteItem(props.row)"
                                ></q-btn>
                              </q-td>
                            </template>
                          </q-table>
                        </div>
                      </div>
                    </div>
                    <!-- ปุ่มควบคุม -->
                    <div class="row">
                      <!-- ปุ่มควบคุม -->
                      <div
                        class="col-md-12 col-xs-12 q-pa-xs row justify-center"
                      >
                        <!-- บันทึก/แก้ไข -->
                        <q-btn
                          :label="btnLabel"
                          type="submit"
                          color="primary"
                          icon="save"
                        />
                        <!-- ยกเลิก -->
                        <q-btn
                          label="ยกเลิก"
                          type="reset"
                          color="primary"
                          flat
                          class="q-pa-xs"
                          icon="clear"
                        />
                        <!-- ออก -->
                        <q-btn
                          icon="logout"
                          label="ออก"
                          color="primary"
                          flat
                          class="q-pa-xs"
                          to="/"
                        />
                        <!-- ย้อนกลับ -->
                        <q-btn
                          color="primary"
                          label="กลับฟอร์มรายงานการพัฒนาตนเอง"
                          no-caps
                          flat
                          icon="skip_previous"
                          to="/FormPlan"
                        >
                          <q-tooltip class="bg-accent"
                            >กลับฟอร์มการพัฒนาตนเอง</q-tooltip
                          >
                        </q-btn>
                        <!-- ไปรายงานการประเมินตนเอง -->
                        <q-btn
                          color="primary"
                          label="ไปฟอร์มรายงานการพัฒนาตนเอง"
                          no-caps
                          flat
                          icon="skip_next"
                          to="/FormReport"
                        >
                          <q-tooltip class="bg-accent"
                            >ไปฟอร์มรายงานการพัฒนาตนเอง</q-tooltip
                          >
                        </q-btn>
                      </div>
                    </div>
                    <!-- ตารางการประเมินตนเอง -->
                    <div class="row">
                      <!-- ตารางการประเมินตนเอง -->
                      <div class="col-md-12 col-xs-12 q-pa-xs">
                        <div class="q-pa-xs">
                          <q-table
                            ref="myTable"
                            title="การประเมินตนเอง"
                            :rows="selfAssessments1"
                            :columns="main_columns"
                            row-key="self_assessment_id"
                            :filter="filter"
                            :loading="loading"
                            selection="multiple"
                            v-model:selected="selected"
                            style="min-height: 100vh"
                            :visible-columns="visibleColumnsAssessment"
                          >
                            <!-- การค้นหา -->
                            <template v-slot:top-right="props">
                              <div class="row">
                                <div class="col-md-4 col-xs-4 q-pa-xs">
                                  <q-select
                                    use-input
                                    color="green"
                                    v-model="career_names_id"
                                    :options="career_names.options"
                                    label="อาชีพเป้าหมาย"
                                    emit-value
                                    map-options
                                    @update:model-value="
                                      (val) => on_career_names(val)
                                    "
                                    @filter="filterCareer_name"
                                  >
                                    <template v-slot:prepend>
                                      <q-icon name="work_history" />
                                    </template>
                                    <template v-slot:option="scope">
                                      <q-item v-bind="scope.itemProps">
                                        <q-item-section avatar>
                                          <q-icon :name="scope.opt.icon" />
                                        </q-item-section>
                                        <q-item-section>
                                          <q-item-label>{{
                                            scope.opt.label
                                          }}</q-item-label>
                                          <q-item-label caption>{{
                                            scope.opt.description
                                          }}</q-item-label>
                                        </q-item-section>
                                      </q-item>
                                    </template>
                                    <template v-slot:append>
                                      <q-icon
                                        name="close"
                                        @click.stop.prevent="on_career_names_"
                                        class="cursor-pointer"
                                      />
                                    </template>
                                  </q-select>
                                </div>
                                <div class="col-md-4 col-xs-4 q-pa-xs">
                                  <q-select
                                    use-input
                                    color="green"
                                    v-model="qualification_names_id"
                                    :options="qualification_names.options"
                                    label="คุณสมบัติ"
                                    emit-value
                                    map-options
                                    @update:model-value="
                                      (val) => on_qualification_names(val)
                                    "
                                    @filter="filterQualification_name"
                                  >
                                    <template v-slot:prepend>
                                      <q-icon name="fact_check" />
                                    </template>
                                    <template v-slot:option="scope">
                                      <q-item v-bind="scope.itemProps">
                                        <q-item-section avatar>
                                          <q-icon :name="scope.opt.icon" />
                                        </q-item-section>
                                        <q-item-section>
                                          <q-item-label>{{
                                            scope.opt.label
                                          }}</q-item-label>
                                          <q-item-label caption>{{
                                            scope.opt.description
                                          }}</q-item-label>
                                        </q-item-section>
                                      </q-item>
                                    </template>
                                    <template v-slot:append>
                                      <q-icon
                                        name="close"
                                        @click.stop.prevent="
                                          on_qualification_names_
                                        "
                                        class="cursor-pointer"
                                      />
                                    </template>
                                  </q-select>
                                </div>
                                <div class="col-md-4 col-xs-4 q-pa-xs">
                                  <q-select
                                    use-input
                                    color="green"
                                    v-model="years_id"
                                    :options="years.options"
                                    label="ปี"
                                    emit-value
                                    map-options
                                    @update:model-value="(val) => on_years(val)"
                                    @filter="filterYear"
                                  >
                                    <template v-slot:prepend>
                                      <q-icon name="fact_check" />
                                    </template>
                                    <template v-slot:option="scope">
                                      <q-item v-bind="scope.itemProps">
                                        <q-item-section avatar>
                                          <q-icon :name="scope.opt.icon" />
                                        </q-item-section>
                                        <q-item-section>
                                          <q-item-label>{{
                                            scope.opt.label
                                          }}</q-item-label>
                                          <q-item-label caption>{{
                                            scope.opt.description
                                          }}</q-item-label>
                                        </q-item-section>
                                      </q-item>
                                    </template>
                                    <template v-slot:append>
                                      <q-icon
                                        name="close"
                                        @click.stop.prevent="on_years_"
                                        class="cursor-pointer"
                                      />
                                    </template>
                                  </q-select>
                                </div>
                                <div class="col-md-4 col-xs-4 q-pa-xs">
                                  <q-select
                                    use-input
                                    color="green"
                                    v-model="months_id"
                                    :options="months.options"
                                    label="เดือน"
                                    emit-value
                                    map-options
                                    @update:model-value="
                                      (val) => on_months(val)
                                    "
                                    @filter="filterMonth"
                                  >
                                    <template v-slot:prepend>
                                      <q-icon name="fact_check" />
                                    </template>
                                    <template v-slot:option="scope">
                                      <q-item v-bind="scope.itemProps">
                                        <q-item-section avatar>
                                          <q-icon :name="scope.opt.icon" />
                                        </q-item-section>
                                        <q-item-section>
                                          <q-item-label>{{
                                            scope.opt.label
                                          }}</q-item-label>
                                          <q-item-label caption>{{
                                            scope.opt.description
                                          }}</q-item-label>
                                        </q-item-section>
                                      </q-item>
                                    </template>
                                    <template v-slot:append>
                                      <q-icon
                                        name="close"
                                        @click.stop.prevent="on_months_"
                                        class="cursor-pointer"
                                      />
                                    </template>
                                  </q-select>
                                </div>
                                <div class="col-md-4 col-xs-4 q-pa-xs">
                                  <q-btn
                                    color="grey-4"
                                    text-color="purple"
                                    glossy
                                    unelevated
                                    icon="camera_enhance"
                                    label="กรองข้อมูล"
                                    @click="getFilterMonth()"
                                  />
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-4 col-xs-4 q-pa-xs">
                                  <q-input
                                    borderless
                                    dense
                                    debounce="300"
                                    v-model="filter"
                                    placeholder="ค้นหาการประเมินตนเเอง"
                                  >
                                    <template v-slot:append>
                                      <q-icon name="search" />
                                    </template>
                                  </q-input>
                                </div>
                                <!-- ส่งออก excel -->
                                <div class="col-md-4 col-xs-4 q-pa-xs">
                                  <q-input
                                    borderless
                                    dense
                                    debounce="300"
                                    v-model="file_export"
                                    placeholder="ชื่อไฟล์นำออก"
                                    outlined
                                  >
                                    <template v-slot:append>
                                      <q-icon name="save" />
                                    </template>
                                  </q-input>
                                </div>
                                <div class="col-md-4 col-xs-4 q-pa-xs">
                                  <q-btn
                                    flat
                                    icon-right="archive"
                                    label="ส่งออกไฟล์"
                                    @click="exportTableRef()"
                                  />
                                </div>
                                <!-- <div class="col-md-4 col-xs-4 q-pa-xs">
                                  <q-btn
                                    flat
                                    rounded
                                    @click="toggleExpansions()"
                                    :icon="
                                      expansionsToggled
                                        ? 'remove_circle'
                                        : 'add_circle'
                                    "
                                    label="ขยาย"
                                    no-caps
                                  ></q-btn>
                                </div> -->
                                <div class="col-md-6 col-xs-6 q-pa-xs">
                                  <q-select
                                    v-model="visibleColumnsAssessment"
                                    multiple
                                    outlined
                                    dense
                                    options-dense
                                    :display-value="$q.lang.table.columns"
                                    emit-value
                                    map-options
                                    :options="main_columns"
                                    option-value="name"
                                    options-cover
                                    style="min-width: 150px"
                                  />
                                </div>
                                <div class="col-md-6 col-xs-6 q-pa-xs">
                                  <q-btn
                                    flat
                                    round
                                    dense
                                    :icon="
                                      props.inFullscreen
                                        ? 'fullscreen_exit'
                                        : 'fullscreen'
                                    "
                                    @click="props.toggleFullscreen"
                                    class="q-ml-md"
                                  />
                                </div>
                              </div>
                            </template>
                            <!-- ปุ่มลบ/ปุ่มแก้ไข -->
                            <!-- ตารางย่อยข้อมูลอ้างอิง -->
                            <template v-slot:body="props">
                              <!-- ปุ่ม +/- แต่ละแถว -->
                              <q-tr :props="props">
                                <q-td auto-width>
                                  <q-toggle
                                    v-model="props.expand"
                                    checked-icon="add"
                                    unchecked-icon="remove"
                                    :label="`กดดู: ${props.row.self_assessment_id}`"
                                    @update:model-value="
                                      subRow(props.row.self_assessment_id)
                                    "
                                  />
                                  <q-btn
                                    icon="mode_edit"
                                    label="แก้ไข"
                                    @click="
                                      OnEdit(props.row.self_assessment_id)
                                    "
                                  ></q-btn>
                                  <q-btn
                                    icon="delete"
                                    label="ลบ"
                                    @click="
                                      onDelete(
                                        props.row.self_assessment_id,
                                        props.row.self_assessment_date
                                      )
                                    "
                                  ></q-btn>
                                </q-td>
                                <q-td
                                  v-for="col in props.cols"
                                  :key="col.name"
                                  :props="props"
                                >
                                  {{ col.value }}
                                </q-td>
                              </q-tr>
                              <!-- ตารางย่อยข้อมูลอ้างอิง -->
                              <q-tr v-show="props.expand" :props="props">
                                <q-td colspan="100%">
                                  <div class="text-left">
                                    <q-table
                                      class="my-sticky-header-table"
                                      title="การพัฒนาตนเอง"
                                      :rows="references2"
                                      :columns="sub_columns"
                                      row-key="reference_id"
                                    >
                                      <template v-slot:body-cell="props1">
                                        <q-td
                                          key="perform_value"
                                          v-if="
                                            props1.row.self_assessment_id ==
                                            props.row.self_assessment_id
                                          "
                                          :props="props1"
                                          :class="
                                            props1.row.self_assessment_id ==
                                            props.row.self_assessment_id
                                              ? 'bg-indigo-5 text-white'
                                              : 'bg-white text-black'
                                          "
                                        >
                                          <div>{{ props1.value }}</div>
                                        </q-td>
                                      </template>
                                    </q-table>
                                  </div>
                                </q-td>
                              </q-tr>
                            </template>
                            <!-- end -->
                            <!-- การใส่สีผลการประเมิน-->
                          </q-table>
                        </div>
                      </div>
                    </div>
                    <!-- end -->
                  </q-form>
                </div>
              </div>
            </q-card>
          </div>
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import axios from "axios";
import { ref } from "vue";
import { useQuasar } from "quasar";
import { exportFile } from "quasar";
// ส่งออกไฟล์ excel
function wrapCsvValue(val, formatFn, row) {
  let formatted = formatFn !== void 0 ? formatFn(val, row) : val;

  formatted =
    formatted === void 0 || formatted === null ? "" : String(formatted);

  formatted = formatted.split('"').join('""');
  /**
   * Excel accepts \n and \r in strings, but some other CSV parsers do not
   * Uncomment the next two lines to escape new lines
   */
  // .split('\n').join('\\n')
  // .split('\r').join('\\r')

  return `"${formatted}"`;
}

export default {
  name: "FormSelfAssessment",
  data() {
    return {
      url: "",
      file_export: "",
      url_api_career_qualification: "",
      url_api_self_assessment: "",
      url_api_plan: "",
      url_api_plan_career: "",
      url_api_qa_plan_career: "",
      message: "Form Self Acessment",
      title: "การประเมินตนเอง",
      currentYear: new Date().getFullYear(),
      selfAssessments: Array,
      selfAssessments_: Array,
      careers: Array,
      career_qualifications: Array,
      member_id: this.$store.getters.myMember_id,
      planCareerId: "",
      filterConditions: {
        // ข้อมูลส่วนตัว
        full_name: "",
        birthday: "",
        telephone: "",
        // ข้อมูลการศึกษา
        institute_name: "",
        faculty_name: "",
        degree_name: "",
        department_name: "",
        graduate_year: "",
        study_year: "",
        // ข้อมูลความพิการ
        disability_name: "",
        project_name: "",
        advisor_name: "",
        // ข้อมูลอาชีพ
        career_group_name: "",
        career_name: "",
        start_date: "",
        // ข้อมูลคุณสมบัติ
        qualification_group_name: "",
        qualification_name: "",
        qa_plan_career: "",
        target_name: "",
        level_name: "",
        // การพัฒนา
        development_name: "",
        plan_title: "",
        importance_name: "",
        plan_channel: "",
        plan_start_date: "",
        plan_end_date: "",
        // การประเมินตนเอง
        perform_name: "",
        self_assessment_date: "",
        year: "",
        month: "",
      },
      selfAssessment: {
        selfAssessmentId: "",
        qualificationId: "",
        month: "",
        assessment: "",
        reference_id: "",
        isVisible: false,
      },
      isEdit: false,
      isEditRef: false,
      status: "บันทึก",
      qualification: {
        options: [],
      },
      btnLabel: "เพิ่มข้อมูล",
      btnLabel_: "เพิ่มผลงาน",
      pagination: ref({
        sortBy: "reference_description",
        descending: false,
        page: 1,
        rowsPerPage: 3,
      }),
      show_dialog: false,
      editedIndex: -1,
      references1_: [],
      references1: [],
      references2: [],
      editedReference: {
        reference_id: "",
        reference_description: "",
      },
      visibleColumnsReference: ref([
        "actions",
        "reference_id",
        "member_id",
        "self_assessment_id",
        "reference",
      ]),
      visibleColumnsAssessment: ref([
        "actions",
        "self_assessment_id",
        "self_assessment_date",
        "career_id",
        "career_name",
        "qa_plan_career_id",
        "qualification_name",
        "target_id",
        "target_name",
        "target_value",
        "perform_id",
        "perform_name",
        "perform_value",
      ]),
      references: [
        { name: "actions", align: "center", label: "Action" },
        // {
        //   name: "reference_id",
        //   align: "center",
        //   label: "รหัสผลงาน",
        //   field: (row) => row.reference_id,
        //   format: (val) => `${val}`,
        //   required: true,
        //   sortable: true,
        // },
        // {
        //   name: "member_id",
        //   align: "center",
        //   label: "รหัสสมาชิค",
        //   field: "member_id",
        //   sortable: true,
        // },
        // {
        //   name: "self_assessment_id",
        //   align: "center",
        //   label: "รหัสการประเมินตนเอง",
        //   field: "self_assessment_id",
        //   sortable: true,
        // },
        {
          name: "reference",
          align: "left",
          label: "แหล่งอ้างอิง/ผลงาน",
          field: "reference_description",
          sortable: true,
        },
      ],
      sub_columns: [
        // { name: "actions", align: "center", label: "Action" },
        {
          name: "reference_id",
          align: "center",
          label: "รหัสการอ้างอิง",
          field: (row) => row.reference_id,
          format: (val) => `${val}`,
          required: true,
          sortable: true,
        },
        // {
        //   name: "self_assessment_id",
        //   align: "center",
        //   label: "รหัสการประเมินตนเอง",
        //   field: "self_assessment_id",
        //   sortable: true,
        // },
        {
          name: "reference_description",
          align: "left",
          label: "แหล่งอ้างอิง/ผลงาน",
          field: "reference_description",
          sortable: true,
        },
      ],
      main_columns: [
        { name: "actions", align: "center", label: "Action" },
        {
          name: "self_assessment_id",
          align: "center",
          label: "รหัสการประเมิน",
          field: (row) => row.self_assessment_id,
          format: (val) => `${val}`,
          required: true,
          sortable: true,
        },
        {
          name: "full_name",
          align: "left",
          label: "ชื่อ-สกุล",
          field: "full_name",
          sortable: true,
        },
        {
          name: "self_assessment_date",
          align: "center",
          label: "วันประเมิน",
          field: "self_assessment_date",
          sortable: true,
        },
        // {
        //   name: "career_id",
        //   align: "center",
        //   label: "รหัสอาชีพ",
        //   field: "career_id",
        //   sortable: true,
        // },
        {
          name: "career_name",
          align: "left",
          label: "อาชีพ",
          field: "career_name",
          sortable: true,
        },
        // {
        //   name: "qa_plan_career_id",
        //   align: "center",
        //   label: "รหัสคุณสมบัติ",
        //   field: "qa_plan_career_id",
        //   sortable: true,
        // },
        {
          name: "qualification_name",
          align: "left",
          label: "คุณสมบัติ",
          field: "qualification_name",
          sortable: true,
        },
        // {
        //   name: "target_id",
        //   align: "center",
        //   label: "รหัสเป้าหมาย",
        //   field: "target_id",
        //   sortable: true,
        // },
        // {
        //   name: "target_name",
        //   align: "left",
        //   label: "เป้าหมาย",
        //   field: "target_name",
        //   sortable: true,
        // },
        {
          name: "target_value",
          align: "center",
          label: "ค่าเป้าหมาย",
          field: "target_value",
          sortable: true,
        },
        // {
        //   name: "perform_id",
        //   align: "center",
        //   label: "รหัสการประเมิน",
        //   field: "perform_id",
        //   sortable: true,
        // },
        // {
        //   name: "perform_name",
        //   align: "left",
        //   label: "การประเมิน",
        //   field: "perform_name",
        //   sortable: true,
        // },
        {
          name: "perform_value",
          align: "center",
          label: "ผลการประเมิน",
          field: "perform_value",
          sortable: true,
        },
      ],
      selected: ref([]),
      selected_reference: ref([]),
      selfAssessments1: [],
      selfAssessments1_: [],
      qa_plan_career_id: "",
      qa_plan_career_: {
        options: [],
      },
      qa_plan_career: {
        options: [],
      },
      plan_career_id: "",
      plan_career_: {
        options: [],
      },
      plan_career: {
        options: [],
      },
      expansionsToggled: false,
      filter: ref(""),
      filter_reference: ref(""),
      loading: ref(false),
      self_assessment_date: "",
      self_assessment_id: "",
      perform_id: "",
      perform_: {
        options: [],
      },
      perform: {
        options: [],
      },
      $q: useQuasar(),
      //-------------------------
      full_names_id: "",
      full_names: {
        options: [],
      },
      full_names_: {
        options: [],
      },
      career_names_id: "",
      career_names: {
        options: [],
      },
      career_names_: {
        options: [],
      },
      qualification_names_id: "",
      qualification_names: {
        options: [],
      },
      qualification_names_: {
        options: [],
      },
      years_id: "",
      years: {
        options: [],
      },
      years_: {
        options: [],
      },
      months_id: "",
      months: {
        options: [],
      },
      months_: {
        options: [],
      },
    };
  },
  methods: {
    yearToDay(day_to_year) {
      var year_to_day = day_to_year.split("/");
      return year_to_day[2] + "/" + year_to_day[1] + "/" + year_to_day[0];
    },
    dayToYear(year_to_day) {
      if (typeof year_to_day == "undefined") year_to_day = "0000/00/00";
      var day_to_year = year_to_day.split("/");
      return day_to_year[2] + "/" + day_to_year[1] + "/" + day_to_year[0];
    },
    // นำออกไฟล์ excel
    exportTable() {
      console.log("Export excel");
      var columns = this.references;
      var rows = this.references1;
      // naive encoding to csv format
      const content = [columns.map((col) => wrapCsvValue(col.label))]
        .concat(
          rows.map((row) =>
            columns
              .map((col) =>
                wrapCsvValue(
                  typeof col.field === "function"
                    ? col.field(row)
                    : row[col.field === void 0 ? col.name : col.field],
                  col.format,
                  row
                )
              )
              .join(",")
          )
        )
        .join("\r\n");

      const status = exportFile(this.file_export, "\ufeff" + content, {
        encoding: "utf-8",
        mimeType: "text/csv;charset=utf-8;",
      });

      if (status !== true) {
        $q.notify({
          message: "Browser denied file download...",
          color: "negative",
          icon: "warning",
        });
      }
    },
    // นำออกไฟล์ excel
    exportTableRef() {
      console.log("Export excel");
      var columns = this.main_columns;
      var rows = this.selfAssessments1;
      // naive encoding to csv format
      const content = [columns.map((col) => wrapCsvValue(col.label))]
        .concat(
          rows.map((row) =>
            columns
              .map((col) =>
                wrapCsvValue(
                  typeof col.field === "function"
                    ? col.field(row)
                    : row[col.field === void 0 ? col.name : col.field],
                  col.format,
                  row
                )
              )
              .join(",")
          )
        )
        .join("\r\n");

      const status = exportFile("self_accessment.csv", "\ufeff" + content, {
        encoding: "utf-8",
        mimeType: "text/csv;charset=utf-8;",
      });

      if (status !== true) {
        $q.notify({
          message: "Browser denied file download...",
          color: "negative",
          icon: "warning",
        });
      }
    },
    //---------------------------------------
    resetForm() {
      this.isEdit = false;
      console.log("isEdit:", this.isEdit);
      this.btnLabel = "เพิ่มข้อมูล";

      console.log("ยกเลิกการบันทึกข้อมูล");
      this.plan_career_id = "";
      this.qa_plan_career_id = "";
      this.self_assessment_date = "";
      this.perform_id = "";

      this.references1 = [];
    },
    getAllUser() {
      console.log(" แสดงข้อมูลทั้งหมด ");
      var self = this;
      axios
        .post(this.url_api_self_assessment, {
          action: "getall",
        })
        .then(function (res) {
          console.log(res);
          self.selfAssessments = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    submitForm() {
      var start_date = this.yearToDay(this.self_assessment_date);
      console.log("start_date:", start_date);
      if (!this.isEdit) {
        // if (this.confirm("คุณต้องการเพิ่มข้อมูลการประเมินตนเองหรือไม่ ?")) {
        this.$q
          .dialog({
            title: "ยืนยัน",
            message: "คุณต้องการเพิ่มข้อมูลการประเมินตนเองหรือไม่ ?",
            persistent: true,
            cancel: true,
          })
          .onOk(() => {
            console.log("บันทึกข้อมูลการประเมินตนเอง");
            const newSelfAssessment = {
              self_assessment_date: start_date,
              qa_plan_career_id: this.qa_plan_career_id,
              perform_id: this.perform_id,
            };
            this.$emit("saveData", newSelfAssessment);
            axios
              .post(this.url_api_self_assessment, {
                action: "insert",
                self_assessment_date: start_date,
                qa_plan_career_id: this.qa_plan_career_id,
                perform_id: this.perform_id,
              })
              .then((res) => {
                console.log("Insert self_assessment:", res.data);
                console.log("references1_.length:", this.references1_.length);
                if (this.references1.length > 0) {
                  this.getUpdateAddRefToDatabase(this.member_id);
                } else {
                  this.getUpdate(this.member_id);
                }
              })
              .catch(function (error) {
                console.log(error);
              });
          })
          .onCancel(() => {});
      } else {
        console.log("บันทึกการแก้ไขตนเอง");
        console.log("qualification:", this.selfAssessment);
        console.log("qa_plan_career_id:", this.qa_plan_career_id);
        const newSelfAssessment = {
          self_assessment_id: this.self_assessment_id,
          self_assessment_date: start_date,
          qa_plan_career_id: this.qa_plan_career_id,
          perform_id: this.perform_id,
        };
        this.$emit("saveData", newSelfAssessment);
        axios
          .post(this.url_api_self_assessment, {
            action: "update",
            self_assessment_id: this.self_assessment_id,
            self_assessment_date: start_date,
            qa_plan_career_id: this.qa_plan_career_id,
            perform_id: this.perform_id,
          })
          .then((res) => {
            console.log("Insert self_assessment:", res.data);
            this.isEdit = false;
            console.log("isEdit:", this.isEdit);
            this.btnLabel = "เพิ่มข้อมูล";
            this.references1 = [];
            this.references1_ = [];
            this.getUpdate(this.member_id);
          })
          .catch(function (error) {
            console.log(error);
          });
      }
    },
    OnEdit(self_assessment_id) {
      console.log("Edit data");
      this.btnLabel = "เปลี่ยนเป็นเพิ่ม";
      this.isEdit = true;

      var self = this;
      console.log("Edit", this.self_assessment_id);
      axios
        .post(this.url_api_self_assessment, {
          action: "edit",
          self_assessment_id: self_assessment_id,
        })
        .then(function (response) {
          console.log("Edit:", response.data);
          self.self_assessment_id = response.data.self_assessment_id;
          // อาชีพเป้าหมาย
          self.plan_career_id = response.data.plan_career_id;
          self.getQualification(self.plan_career_id);
          // คุณสมบัติตามอาชีพเป้าหมาย
          self.qa_plan_career_id = response.data.qa_plan_career_id;
          // self.qa_plan_career.value
          // ประเมินผลตนเอง
          self.perform_id = response.data.perform_id;
          // วันที่ประเมินผล
          self.self_assessment_date = self.dayToYear(
            response.data.self_assessment_date
          );

          // เอกสารอ้างอิง/ผลงาน
          self.getReferenceBySelf_Assessment_id(self.self_assessment_id);
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    onDelete(self_assessment_id, self_assessment_date) {
      this.$q
        .dialog({
          title: "ยืนยัน",
          message:
            "คุณต้องการลบผลการประเมินวัน " +
            self_assessment_date +
            " หรือไม่ ?",
          persistent: true,
          cancel: true,
        })
        .onOk(() => {
          var self = this;
          axios
            .post(this.url_api_self_assessment, {
              action: "delete",
              self_assessment_id: self_assessment_id,
            })
            .then(function (response) {
              console.log(response);
              self.getUpdate(self.member_id);
            })
            .catch(function (error) {
              console.log(error);
            });
        });
    },
    onNext() {
      this.$router.replace({ name: "FormPivotTable" });
    },
    onPrevious() {
      this.$router.replace({ name: "FormPlan" });
    },
    // checked
    getUpdateAddRefToDatabase(member_id) {
      console.log(" แสดงข้อมูล การประเมินตนเอง ", member_id);
      var self = this;
      axios
        .post(this.url_api_self_assessment, {
          action: "getAll",
          member_id: member_id,
        })
        .then(function (res) {
          self.selfAssessments1 = res.data;
          self.selfAssessments1_ = res.data;
          console.log("self_assessment:", self.selfAssessments1);
          self.addRefToDatabase(self.selfAssessments1);
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    getUpdate(member_id) {
      console.log(" แสดงข้อมูล การประเมินตนเอง ", member_id);
      var self = this;
      axios
        .post(this.url_api_self_assessment, {
          action: "getAll",
          member_id: member_id,
        })
        .then(function (res) {
          self.selfAssessments1 = res.data;
          self.selfAssessments1_ = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    // checked
    getCareer(member_id) {
      console.log(" ข้อมูลอาชีพ สมาชิค", member_id);
      var self = this;
      axios
        .post(this.url_api_plan_career, {
          action: "get_plan_career_by_member_id",
          member_id: member_id,
        })
        .then(function (res) {
          var plan_career_id = res.data.map((item) => item.plan_career_id);
          var career_name = res.data.map((item) => item.career_name);
          self.plan_career.options.splice(0);
          for (var i = 0; i < plan_career_id.length; i++) {
            self.plan_career.options.push({
              label: career_name[i],
              value: plan_career_id[i],
            });
          }
          self.plan_career_.options = self.plan_career.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    // checked
    getQualification(plan_career_id) {
      console.log(" แสดงข้อมูลคุณสมบัติ รหัสแผนอาชีพ: ", plan_career_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_qa_plan_career_by_plan_career_id",
          plan_career_id: plan_career_id,
        })
        .then(function (res) {
          console.log("qa_plan_career:", res.data);
          var qa_plan_career_id = res.data.map(
            (item) => item.qa_plan_career_id
          );
          var qualification_name = res.data.map(
            (item) => item.qualification_name
          );
          var level_name = res.data.map((item) => item.level_description);
          var target_name = res.data.map((item) => item.target_name);
          self.qa_plan_career.options.splice(0);
          for (var i = 0; i < qa_plan_career_id.length; i++) {
            self.qa_plan_career.options.push({
              label: qualification_name[i],
              value: qa_plan_career_id[i],
              description: level_name[i] + " " + target_name[i],
            });
          }
          self.qa_plan_career_.options = self.qa_plan_career.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getPerform() {
      console.log(" ข้อมูลค่าเป้าหมาย ");
      var self = this;
      axios
        .post(this.url_api_self_assessment, {
          action: "getPerform",
        })
        .then(function (res) {
          var perform_id = res.data.map((item) => item.perform_id);
          var perform_name = res.data.map((item) => item.perform_name);
          self.perform.options.splice(0);
          for (var i = 0; i < perform_id.length; i++) {
            self.perform.options.push({
              label: perform_name[i],
              value: perform_id[i],
            });
          }
          self.perform_.options = self.perform.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filterPerform(val, update) {
      if (val === "") {
        update(() => {
          this.perform.options = this.perform_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.perform.options = this.perform_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    filterQa_plan_career(val, update) {
      if (val === "") {
        update(() => {
          this.qa_plan_career.options = this.qa_plan_career_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.qa_plan_career.options = this.qa_plan_career_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    filterPlan_career(val, update) {
      if (val === "") {
        update(() => {
          this.plan_career.options = this.plan_career_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.plan_career.options = this.plan_career_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },

    addRow() {
      // เพิ่มข้อมูลผลงาน โดยที่ไม่ได้ยังไม่ได้เพิ่มลงฐานข้อมูล
      if (!this.isEdit) {
        console.log("Edit1==true");
        // แก้ไขช้อมูล
        if (this.editedIndex > -1) {
          Object.assign(
            this.references1[this.editedIndex],
            this.editedReference
          );
        }
        // เพิ่มช้อมูล
        else {
          this.references1.push(this.editedReference);
          this.references1_.push(this.editedReference.reference_description);
          console.log(
            "references1:",
            this.editedReference.reference_description
          );
        }
        this.close();
      }
      // ------------------------------------
      // เพิ่มข้อมูลผลงาน ลงฐานข้อมูล
      else {
        console.log("Edit1==false");
        // แก้ไขช้อมูล
        if (this.editedIndex > -1) {
          Object.assign(
            this.references1[this.editedIndex],
            this.editedReference
          );
        }
        // เพิ่มช้อมูล
        else {
          this.references1.push(this.editedReference);
          this.references1_.push(this.editedReference.reference_description);
          console.log(
            "references1+:",
            this.editedReference.reference_description
          );
        }
        this.close();

        // เพิ่มข้อมูลผลงาน ลงฐานข้อมูลทีละแถว
        if (!this.isEditRef) {
          console.log("Add new in Edit");
          var reference_description =
            this.editedReference.reference_description;
          var self_assessment_id = this.self_assessment_id;
          var member_id = this.member_id;

          console.log(
            " add new in Edit+: ",
            self_assessment_id + "/" + member_id + "/" + reference_description
          );
          var self = this;
          axios
            .post(this.url_api_self_assessment, {
              action: "add_reference",
              self_assessment_id: self_assessment_id,
              reference_description: reference_description,
            })
            .then(function (res) {
              console.log("add_reference:", res.data);
              self.getUpdateReference_(member_id, self_assessment_id);
            })
            .catch(function (error) {
              console.log(error);
            });
        }
        // บันทึกการแก้ไข ลงฐานข้อมูล
        else {
          console.log("Update data reference");
          this.btnLabel_ = "เพิ่มผลงาน";
          this.isEditRef = false;
          var self = this;
          var reference_id = this.editedReference.reference_id;
          var reference_description =
            this.editedReference.reference_description;
          console.log("reference_id:", reference_id);
          console.log("reference_description:", reference_description);
          axios
            .post(this.url_api_self_assessment, {
              action: "edit_reference_by_reference_id",
              reference_id: reference_id,
              reference_description: reference_description,
            })
            .then(function (res) {
              console.log("update reference:", res.data);
              // self.references1 = res.data;
            })
            .catch(function (error) {
              console.log(error);
            });
        }
      }
    },
    addRow1() {
      // เพิ่มข้อมูลผลงาน โดยที่ไม่ได้ยังไม่ได้เพิ่มลงฐานข้อมูล
      if (!this.isEdit) {
        console.log("Edit1==true");
        // แก้ไขช้อมูล
        if (this.editedIndex > -1) {
          Object.assign(
            this.references1[this.editedIndex],
            this.editedReference
          );
        }
        // เพิ่มช้อมูล
        else {
          this.references1.push(this.editedReference);
          this.references1_.push(this.editedReference.reference_description);
          console.log(
            "references1:",
            this.editedReference.reference_description
          );
        }
        this.close();
      }
      // ------------------------------------
      // เพิ่มข้อมูลผลงาน ลงฐานข้อมูล
      else {
        console.log("Edit1==false");
        // แก้ไขช้อมูล
        if (this.editedIndex > -1) {
          Object.assign(
            this.references1[this.editedIndex],
            this.editedReference
          );
        }
        // เพิ่มช้อมูล
        else {
          this.references1.push(this.editedReference);
          this.references1_.push(this.editedReference.reference_description);
          console.log(
            "references1+:",
            this.editedReference.reference_description
          );
        }
        this.close();

        // เพิ่มข้อมูลผลงาน ลงฐานข้อมูลทีละแถว
        if (!this.isEditRef) {
          console.log("Add new in Edit");
          // console.log(" เพิ่มข้อมูลผลงานลงฐานข้อมูล: ", self_assessment_id);
          // var self = this;
          // axios
          //   .post(this.url_api_self_assessment, {
          //     action: "add_reference_by_self_assessment_id",
          //     self_assessment_id: self_assessment_id,
          //   })
          //   .then(function (res) {
          //     console.log("reference:", res.data);
          //     self.references1 = res.data;
          //   })
          //   .catch(function (error) {
          //     console.log(error);
          //   });
          var reference_description =
            this.editedReference.reference_description;
          var self_assessment_id = this.self_assessment_id;

          console.log(
            " self_assessment_id/reference_description: ",
            self_assessment_id + "/" + reference_description
          );
          var self = this;
          axios
            .post(this.url_api_self_assessment, {
              action: "add_reference",
              self_assessment_id: self_assessment_id,
              reference_description: reference_description,
            })
            .then(function (res) {
              console.log("add_reference:", res.data);
              self.getUpdateReference_(self.member_id, self_assessment_id);
            })
            .catch(function (error) {
              console.log(error);
            });
        }
        // บันทึกการแก้ไข ลงฐานข้อมูล
        else {
          console.log("Update data reference");
          this.btnLabel_ = "เพิ่มผลงาน";
          this.isEditRef = false;
          var self = this;
          var reference_id = this.editedReference.reference_id;
          var reference_description =
            this.editedReference.reference_description;
          console.log("reference_id:", reference_id);
          console.log("reference_description:", reference_description);
          axios
            .post(this.url_api_self_assessment, {
              action: "edit_reference_by_reference_id",
              reference_id: reference_id,
              reference_description: reference_description,
            })
            .then(function (res) {
              console.log("update reference:", res.data);
              // self.references1 = res.data;
            })
            .catch(function (error) {
              console.log(error);
            });
        }
      }
    },
    addRefToDatabase(selfAssessments1) {
      if (!this.isEditRef) {
        console.log("บันทึกข้อมูล ref");
        var length = this.selfAssessments1.length;
        var last_self_assessment_id =
          selfAssessments1[length - 1].self_assessment_id;
        console.log("self_assessment_id:", last_self_assessment_id);
        const newReference = {
          self_assessment_id: last_self_assessment_id,
          references: this.references1_,
        };
        this.$emit("saveData", newReference);
        axios
          .post(this.url_api_self_assessment, {
            action: "insert_reference",
            self_assessment_id: last_self_assessment_id,
            references: this.references1_,
          })
          .then((res) => {
            console.log("insert_reference:", res.data);
            this.references1 = [];
            this.references_ = [];
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        console.log("Update ข้อมูล ref");
        axios
          .post(this.url_api_self_assessment, {
            action: "update_reference",
          })
          .then((response) => {
            console.log("update_reference:", response.data);
            this.isEditRef = false;
            console.log("isEdit:", this.isEditRef);
            this.btnLabel_ = "เพิ่มผลงาน";
          })
          .catch(function (error) {
            console.log(error);
          });
      }
    },

    getUpdateReference(member_id) {
      console.log(" แสดงข้อมูลการอ้างอิงทั้งหมด ", member_id);
      var self = this;
      axios
        .post(this.url_api_self_assessment, {
          action: "getAllReference",
          member_id: member_id,
        })
        .then(function (res) {
          self.references2 = res.data;
          console.log("reference:", self.references2);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getUpdateReference_(member_id, self_assessment_id) {
      console.log(
        " แสดงข้อมูลการอ้างอิงทั้งหมด ",
        member_id + "/" + self_assessment_id
      );

      var self = this;
      axios
        .post(this.url_api_self_assessment, {
          action: "getAllReference_",
          member_id: member_id,
          self_assessment_id: self_assessment_id,
        })
        .then(function (res) {
          self.references1 = res.data;
          console.log("Update reference:", self.references1);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    deleteItem(item) {
      if (this.isEdit) {
        var self_assessment_id = item.self_assessment_id;
        var reference_id = item.reference_id;
        var member_id = item.member_id;
        console.log(
          " ลบข้อมูลรหัสการประเมินตนเอง/เอกสารอ้างอิง: ",
          self_assessment_id + "/" + member_id + "/" + reference_id
        );
        this.$q
          .dialog({
            title: "ยืนยัน",
            message:
              "คุณต้องการลบรหัสการประเมินตนเอง/ผลงาน: " +
              self_assessment_id +
              "/" +
              member_id +
              "/" +
              reference_id +
              " หรือไม่ ?",
            persistent: true,
            cancel: true,
          })
          .onOk(() => {
            var self = this;
            axios
              .post(this.url_api_self_assessment, {
                action: "delete_reference_by_reference_id",
                reference_id: reference_id,
              })
              .then(function (res) {
                console.log("reference:", res.data);
                self.getUpdateReference_(member_id, self_assessment_id);
              })
              .catch(function (error) {
                console.log(error);
              });
          });
      } else {
        console.log("item:", item.reference_description);
        const index = this.references1.indexOf(item);
        confirm("คุณต้องการลบ: " + item.reference_description) &&
          this.references1.splice(index, 1);
      }
    },
    editItem(item) {
      console.log("Edit data reference");
      this.btnLabel_ = "แก้ไขผลงาน";
      this.isEditRef = true;

      var self_assessment_id = item.self_assessment_id;
      var reference_id = item.reference_id;

      console.log(
        " ลบข้อมูลรหัสการประเมินตนเอง/เอกสารอ้างอิง: ",
        self_assessment_id + "/" + reference_id
      );
      console.log(" isEditRef: ", this.isEditRef);
      console.log("item:", this.references1.indexOf(item));
      this.editedIndex = this.references1.indexOf(item);
      console.log("object", Object.assign({}, item));
      this.editedReference = Object.assign({}, item);
      this.show_dialog = true;
      // }
    },
    close() {
      this.show_dialog = false;
      setTimeout(() => {
        this.editedReference = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },
    // end
    // ตารางย่อยตารางอ้างอิง
    toggleExpansions() {
      console.log("toggleExpansions:", this.selfAssessments1);
      this.expansionsToggled = !this.expansionsToggled;
      let rowArray = [];
      if (this.expansionsToggled) {
        rowArray = this.selfAssessments1_.map((row) => row.self_assessment_id);
        console.log("self_assessment_id:", rowArray);
        this.getUpdateReference(this.member_id);
        // } else {
        //   rowArray = [];
      }
      this.$refs.myTable.setExpanded(rowArray);
    },
    subRow(self_assessment_id) {
      console.log("self_assessment_id:", self_assessment_id);
      this.getReferenceBySelf_Assessment_id_(self_assessment_id);
    },
    getReferenceBySelf_Assessment_id(self_assessment_id) {
      console.log(" แสดงข้อมูลแหล่งอ้างอิง: ", self_assessment_id);
      var self = this;
      axios
        .post(this.url_api_self_assessment, {
          action: "get_reference_by_self_assessment_id",
          self_assessment_id: self_assessment_id,
        })
        .then(function (res) {
          console.log("reference+:", res.data);
          self.references1 = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getReferenceBySelf_Assessment_id_(self_assessment_id) {
      console.log(" แสดงข้อมูลแหล่งอ้างอิง: ", self_assessment_id);
      var self = this;
      axios
        .post(this.url_api_self_assessment, {
          action: "get_reference_by_self_assessment_id",
          self_assessment_id: self_assessment_id,
        })
        .then(function (res) {
          console.log("reference+:", res.data);
          self.references2 = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    //------------------------------
    get_Month_QaPlanCareer() {
      var member_id = Number(this.$store.getters.myMember_id);
      console.log(" แสดงข้อมูลคุณสมบัติ member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_month_table",
          member_id: member_id,
        })
        .then(function (res) {
          console.log("month_table:+", res.data);
          self.month_table = res.data;

          self.selfAssessments1 = res.data;
          self.selfAssessments1_ = res.data;
          console.log("self_assessment:+", self.selfAssessments1);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    on_full_name_() {
      this.full_names_id = "";
      this.on_full_name("");
    },
    on_full_name(full_name) {
      console.log(" full_name:", full_name);
      this.filterConditions.full_name = full_name;
    },
    getFullName() {
      var member_id = Number(this.$store.getters.myMember_id);
      console.log(" full_name:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_full_name",
          // member_id: member_id,
        })
        .then(function (response) {
          console.log("FullName:", response.data);
          self.full_names.options = response.data.map((item) => ({
            label: item.full_name,
            value: item.full_name,
          }));
          self.full_names_.options = self.full_names.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filterFull_name(val, update) {
      if (val === "") {
        update(() => {
          this.full_names.options = this.full_names_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.full_names.options = this.full_names_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    on_career_names_() {
      this.career_names_id = "";
      this.on_career_names("");
    },
    on_career_names(career_name) {
      console.log(" career:", career_name);
      this.filterConditions.career_name = career_name;
    },
    getCareer_name() {
      var member_id = Number(this.$store.getters.myMember_id);
      console.log(" แสดงข้อมูลคุณสมบัติ member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_career",
          member_id: member_id,
        })
        .then(function (res) {
          console.log("career_name:", res.data);
          self.career_names.options = res.data.map((item) => ({
            label: item.career_name,
            value: item.career_name,
          }));
          self.career_names_.options = self.career_names.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filterCareer_name(val, update) {
      if (val === "") {
        update(() => {
          this.career_names.options = this.career_names_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.career_names.options = this.career_names_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    on_qualification_names_() {
      this.qualification_names_id = "";
      this.on_qualification_names("");
    },
    on_qualification_names(qualification_name) {
      console.log(" qualification_name:", qualification_name);
      this.filterConditions.qualification_name = qualification_name;
    },
    getQualification_name() {
      var member_id = Number(this.$store.getters.myMember_id);
      console.log(" แสดงข้อมูลคุณสมบัติ member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_qualification_name",
          member_id: member_id,
        })
        .then(function (res) {
          console.log("Qualification_name:", res.data);
          self.qualification_names.options = res.data.map((item) => ({
            label: item.qualification_name,
            value: item.qualification_name,
          }));
          self.qualification_names_.options = self.qualification_names.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filterQualification_name(val, update) {
      if (val === "") {
        update(() => {
          this.qualification_names.options = this.qualification_names_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.qualification_names.options =
          this.qualification_names_.options.filter(
            (v) => v.label.indexOf(needle) > -1
          );
      });
    },
    on_years_() {
      this.years_id = "";
      this.on_years("");
    },
    on_years(year) {
      console.log(" year:", year);
      this.filterConditions.year = year;
    },
    getYear() {
      var member_id = Number(this.$store.getters.myMember_id);
      console.log(" แสดงข้อมูลคุณสมบัติ member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_year",
          member_id: member_id,
        })
        .then(function (res) {
          console.log("Year:", res.data);
          self.years.options = res.data.map((item) => ({
            label: item.Year,
            value: item.Year,
          }));
          self.years_.options = self.years.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filterYear(val, update) {
      if (val === "") {
        update(() => {
          this.years.options = this.years_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.years.options = this.years_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    on_months_() {
      this.months_id = "";
      this.on_months("");
    },
    on_months(month) {
      console.log(" month:", month);
      this.filterConditions.month = month;
    },
    getMonth() {
      var member_id = Number(this.$store.getters.myMember_id);
      console.log(" แสดงข้อมูลคุณสมบัติ member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_month",
          member_id: member_id,
        })
        .then(function (res) {
          console.log("Year:", res.data);
          self.months.options = res.data.map((item) => ({
            label: item.Month,
            value: item.Month,
          }));
          self.months_.options = self.months.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filterMonth(val, update) {
      if (val === "") {
        update(() => {
          this.months.options = this.months_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.months.options = this.months_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    getFilterYear() {
      var member_id = Number(this.$store.getters.myMember_id);
      console.log(" member_id:", member_id);
      console.log(" full_name:", this.filterConditions.full_name);
      console.log(" career_name:", this.filterConditions.career_name);
      console.log(
        " qualification_name:",
        this.filterConditions.qualification_name
      );
      console.log(" year:", this.filterConditions.year);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_filter_year",
          member_id: member_id,
          full_name:
            this.filterConditions.full_name == ""
              ? "%"
              : this.filterConditions.full_name,
          career_name:
            this.filterConditions.career_name == ""
              ? "%"
              : this.filterConditions.career_name,
          qualification_name:
            this.filterConditions.qualification_name == ""
              ? "%"
              : this.filterConditions.qualification_name,
          year:
            this.filterConditions.year == "" ? "%" : this.filterConditions.year,
        })
        .then(function (res) {
          console.log("table:+", res.data);
          self.year_table = res.data;

          self.selfAssessments1 = res.data;
          self.selfAssessments1_ = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getFilterMonth() {
      var member_id = Number(this.$store.getters.myMember_id);
      console.log(" แสดงข้อมูลคุณสมบัติ member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_filter_month",
          member_id: member_id,
          career_name:
            this.filterConditions.career_name == ""
              ? "%"
              : this.filterConditions.career_name,
          qualification_name:
            this.filterConditions.qualification_name == ""
              ? "%"
              : this.filterConditions.qualification_name,
          year:
            this.filterConditions.year == "" ? "%" : this.filterConditions.year,
          month:
            this.filterConditions.month == ""
              ? "%"
              : this.filterConditions.month,
        })
        .then(function (res) {
          console.log("SelfAssessments1", res.data);
          self.month_table = res.data;

          self.selfAssessments1 = res.data;
          self.selfAssessments1_ = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  },
  mounted() {
    this.getCareer(this.member_id);
    this.getPerform();

    this.getCareer_name();
    this.getQualification_name();
    this.getYear();
    this.getMonth();
    this.getFilterMonth();
  },

  created() {
    var www = this.$store.getters.myWWW;
    var folder = "icp_v1/self_assessment_form/";
    var local_ = "http://localhost:85/icp2022/" + folder;
    var www_ = "https://icp2022.net/" + folder;

    if (!www) {
      this.url = "api-member.php";
      this.url_api_career_qualification = local_ + "api-qa-plan-career.php";
      this.url_api_self_assessment = local_ + "api-self-assessment.php";
      this.url_api_plan = local_ + "api-plan.php";
      this.url_api_plan_career = local_ + "api-plan-career.php";
      this.url_api_qa_plan_career = local_ + "api-qa-plan-career.php";
    } else {
      this.url = "api-member.php";
      this.url_api_career_qualification = www_ + "api-qa-plan-career.php";
      this.url_api_self_assessment = www_ + "api-self-assessment.php";
      this.url_api_plan = www_ + "api-plan.php";
      this.url_api_plan_career = www_ + "api-plan-career.php";
      this.url_api_qa_plan_career = www_ + "api-qa-plan-career.php";
    }
  },
};
</script>

<style scoped></style>
<style lang="sass">
.my-sticky-header-table
  height: 310px
  .q-table__top,
  .q-table__bottom,
  thead tr:first-child th
    background-color: #c1f4cd
  thead tr th
    position: sticky
    z-index: 1
  thead tr:first-child th
    top: 0
  &.q-table--loading thead tr:last-child th
    top: 48px
</style>
