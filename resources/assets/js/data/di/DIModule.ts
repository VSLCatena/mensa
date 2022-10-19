import DependencyContainer from 'tsyringe/dist/typings/types/dependency-container';

export abstract class DIModule {
  abstract register(container: DependencyContainer): void;
}
