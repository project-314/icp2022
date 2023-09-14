<template>
  <q-card flat class="bg-white text-black">
    <q-card-section class="bg-blue-14">
      <h4 class="text-h5 text-white q-ma-xs text-center">
        {{ title }}
      </h4>
    </q-card-section>
    <div class="row full-width">
      <div class="col-md-12 col-xs-12 q-pa-xs">
        <q-form class="q-gutter-md">
          <div class="row">
            <div class="col-md-12 col-xs-12 q-pa-xs">
              <div class="q-ma-xs justify-center">
                <q-img
                  v-if="
                    $store.getters.myAuthenticate &&
                    $store.getters.myStatus == 'user'
                  "
                  placeholder-src="~assets/images/pics_topic_103.jpg"
                  src="~assets/images/user.jpg"
                  fit="cover"
                ></q-img>
                <q-img
                  v-if="
                    $store.getters.myAuthenticate &&
                    $store.getters.myStatus == 'admin'
                  "
                  placeholder-src="~assets/images/pics_topic_103.jpg"
                  src="~assets/images/admin.jpg"
                  fit="cover"
                ></q-img>
                <q-img
                  v-if="
                    $store.getters.myAuthenticate &&
                    $store.getters.myStatus == 'suser'
                  "
                  placeholder-src="~assets/images/pics_topic_103.jpg"
                  src="~assets/images/suser.jpg"
                  fit="cover"
                ></q-img>
                <q-img
                  v-if="!$store.getters.myAuthenticate"
                  placeholder-src="~assets/images/pics_topic_103.jpg"
                  src="~assets/images/pics_topic_103.jpg"
                  fit="cover"
                ></q-img>
              </div>
            </div>
          </div>
        </q-form>
      </div>
    </div>
  </q-card>
</template>

<script>
export default {
  name: "IndexPage",
  data() {
    return {
      title: "Individualized Career Planning",
    };
  },
};
</script>

<style></style>
