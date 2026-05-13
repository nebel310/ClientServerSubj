function UserList({ users }) {
  return (
    <ul>
      {users.map((user) => (
        <li key={user.id}>
          {user.login} — {user.html_url}
        </li>
      ))}
    </ul>
  );
}

export default UserList;