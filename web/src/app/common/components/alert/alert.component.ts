import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-alert',
  templateUrl: './alert.component.html',
  styleUrl: './alert.component.scss'
})
export class AlertComponent {
  @Input({ required: true }) isVisible: boolean = false;
  @Input({ required: true }) alertType: 'primary' | 'secondary' | 'success' | 'danger' | 'warning' | 'info' | 'light' | 'dark' = 'primary';
  @Input({ required: true }) iconClass: string = '';

  get alertClass(): string {
    return `alert-${this.alertType}`;
  }
}
