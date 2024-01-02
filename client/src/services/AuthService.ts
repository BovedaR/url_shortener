import type { User } from '../types/User';
import { BaseService } from './BaseService';

export class AuthService extends BaseService {
  constructor() {
    super("auth");
  }

  login(credentials: { name: string; password: string }) {
    return this.post<User>(`login`, credentials);
  }

  logout() {
    return this.post<User>(`logout`, {});
  }

  register(user: User) {
    return this.post<User>(`register`, user);
  }
}