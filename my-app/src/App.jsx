import { useEffect, useState } from 'react';
import UserList from './UserList';

function App() {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    fetch('https://api.github.com/users')
      .then((response) => {
        if (!response.ok) throw new Error('Ошибка сети');
        return response.json();
      })
      .then((data) => {
        setUsers(data);
        setLoading(false);
      })
      .catch(() => {
        setError('Ошибка загрузки данных');
        setLoading(false);
      });
  }, []);

  if (loading) return <p>Загрузка...</p>;
  if (error) return <p>{error}</p>;

  return (
    <div>
      <h1>Список пользователей GitHub</h1>
      <UserList users={users} />
    </div>
  );
}

export default App;