<template>
  <div v-if="$apollo.loading">Loading...</div>
  <div v-else>
    <h1>Apollo test ..</h1>
    <ul class="users">
      <li v-for="user in users">{{ user.name }} , {{ user.email }}</li>
    </ul>
  </div>
</template>

<script>
import gql from "graphql-tag";
export default {
  name: "Apollo",

  created() {
    this.$apollo
      .mutate({
        mutation: gql`
          mutation($password: String!, $email: String!) {
            signIn(password: $password, email: $email) {
              id
              name
              email
              api_token
            }
          }
        `,
        variables: { password: "xx1122", email: "author_test@haxibiao.com" }
      })
      .then(mutationResult => {
        this.token = mutationResult.data.signIn.api_token;
      });

    // this.$apollo.mutate({
    //      mutation: gql`
    //        mutation ($id: Int!, $email: String!) {
    //          updateUserEmail(id:$id, email: $email){
    //            id
    //            name
    //            email
    //          }
    //        }
    //      `,
    //      variables: { id: 2, email: 'lz@hxb.com' }
    //    }).then(mutationResult => {
    //      // Do stuff with the result.
    //      console.log(`The id 2 email is: ${mutationResult.data.updateUserEmail}`)
    //    });
  },

  apollo: {
    users: gql`
      query users {
        users {
          name
          email
        }
      }
    `
  },

  data() {
    return {
      token: null,
      users: []
    };
  }
};
</script>

<style lang="css" scoped>
</style>
