import { ComponentFixture, TestBed } from '@angular/core/testing';

import { MenuCollapseComponent } from './menu-collapse.component';

describe('MenuCollapseComponent', () => {
	let component: MenuCollapseComponent;
	let fixture: ComponentFixture<MenuCollapseComponent>;

	beforeEach(async () => {
		await TestBed.configureTestingModule({
			declarations: [MenuCollapseComponent]
		}).compileComponents();

		fixture = TestBed.createComponent(MenuCollapseComponent);
		component = fixture.componentInstance;
		fixture.detectChanges();
	});

	it('should create', () => {
		expect(component).toBeTruthy();
	});
});
