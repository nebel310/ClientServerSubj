import { Injectable, NotFoundException, BadRequestException } from '@nestjs/common';
import { User } from './user.entity';
import { CreateUserDto } from './create-user.dto';

@Injectable()
export class UsersService {
  private users: User[] = [];
  private idCounter = 1;

  findAll(): User[] {
    return this.users;
  }

  findOne(id: number): User {
    const user = this.users.find(u => u.id === id);
    if (!user) {
      throw new NotFoundException(`Пользователь с id ${id} не найден`);
    }
    return user;
  }

  create(createUserDto: CreateUserDto): User {
    const existingUser = this.users.find(u => u.email === createUserDto.email);
    if (existingUser) {
      throw new BadRequestException('Пользователь с таким email уже существует');
    }

    const newUser: User = {
      id: this.idCounter++,
      ...createUserDto,
    };
    this.users.push(newUser);
    return newUser;
  }
}