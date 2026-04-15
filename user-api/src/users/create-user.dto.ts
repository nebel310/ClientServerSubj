import { IsString, IsEmail, IsInt, IsOptional, Min, Max } from 'class-validator';

export class CreateUserDto {
  @IsString({ message: 'Имя должно быть строкой' })
  name: string;

  @IsEmail({}, { message: 'Некорректный email' })
  email: string;

  @IsOptional()
  @IsInt({ message: 'Возраст должен быть целым числом' })
  @Min(1, { message: 'Возраст должен быть не менее 1' })
  @Max(150, { message: 'Возраст должен быть не более 150' })
  age?: number;
}