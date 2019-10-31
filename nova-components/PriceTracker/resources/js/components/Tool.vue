<template>
  <div class="app min-h-screen min-v-screen p-8 bg-grey-lightest font-sans">
    <div class="row sm:flex">
      <div class="col sm:w-1/2">
        <div class="box border rounded flex flex-col shadow bg-white">
          <div class="box__title bg-grey-lighter px-3 py-2 border-b">
            <h3 class="text-sm text-grey-darker font-medium">导入抖音视频</h3>
          </div>
          <textarea
            placeholder="请填写从抖音复制的分享链接"
            class="text-grey-darkest flex-1 p-2 m-1 bg-transparent"
            name="link"
            id="link"
            v-model="link"
          ></textarea>
        </div>
      </div>
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded"
        style="background:#252d37;"
        id="button"
        @click="submit"
      >提&nbsp;&nbsp;交</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      link: ""
    };
  },
  methods: {
    submit() {
      // 获取当前用户id
      const userId = Nova.config.userId;

      // 请求 api controller
      axios
        .post("/api/article/resolverDouyin", {
          params: {
            share_link: this.link,
            user_id: userId
          }
        })
        .then(function(response) {
          if (response.data.id) {
            window.open(
              "/nova/resources/articles/" + response.data.id,
              "target"
            );
          } else {
            alert(response.data);
          }
        })
        .catch(function(error) {
          alert(
            "发布视频失败，联系 zengdawei@haxibiao.com 或者向其他的小哥求助"
          );
        });
    }
  }
};
</script>

<style>
/* Scoped Styles */
</style>
